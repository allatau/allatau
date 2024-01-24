<?php

namespace App\Jobs;

use App\Enums\TaskStatus;
use App\Models\ComputingResource;
use App\Models\Task;
use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

use RdKafka\Conf;
use RdKafka\Producer;
use RdKafka\TopicConf;

// php8 artisan job:dispatch SendTaskJob 1


class SendTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fibonacci_rpc;
    private $task;
    private $computing_resource;

    public function __construct($task_id)
    {
        $this->task = Task::find($task_id);
        $this->computing_resource = ComputingResource::find($this->task->computing_resource_id);
    }


    /**
     * Create a new job instance.
     *
     * @return void
     */
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $task_array = array_merge($this->task->getAttributes(), ['computing_resource' => $this->computing_resource->getAttributes()]);

//        print_r($task_array);
        Task::where('id',$this->task->id)->update(['status'=>TaskStatus::CREATING]);

        $computational_model = null;

        if ($task_array["computational_model_resource"] != "") {
            $computational_model = [
                "resource" => $task_array["computational_model_resource"],
                "type" => "http"
            ];
        }

        $taskInstance = [
            "id" => $task_array["id"],
            "computational_model" => $computational_model,
            "computing_resource" => [
                "hostname" => $task_array["computing_resource"]["host"],
                "port" => (int) $task_array["computing_resource"]["port"],
                "username" => $task_array["computing_resource"]["login"],
                "password" => $task_array["computing_resource"]["password"],
            ],
            "solver" => [
                "software" => "openfoam",
                "version" => "9",
                "script" => explode('\n', $task_array["script"])
//                "script" => [ "source /opt/openfoam9/etc/bashrc", "blockMesh", "icoFoam" ]
            ],
            "converter_service" => json_decode($task_array["converter_service"]),
            "numerical_model" => json_decode($task_array["numerical_model"]),

            /*
        "computing_resource" => [
                "hostname" => $task_array["computing_resource"]["host"],
                "port" => $task_array["computing_resource"]["port"],
                "username" => $task_array["computing_resource"]["login"],
                "password" => $task_array["computing_resource"]["password"],
            ]
            */
        ];

//        $taskInstanceJsonString = json_encode($taskInstance);
//        $test = json_decode($taskInstanceJsonString);

        $response = Http::post('http://localhost:7070/tasks/', $taskInstance);

        // Получить ответ
        $responseData = $response->json();

        Task::where('id',$this->task->id)->update(['status'=>TaskStatus::CREATING]);

//        return Task::
    }
}
