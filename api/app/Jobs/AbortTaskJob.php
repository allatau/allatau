<?php

namespace App\Jobs;

use App\Enums\TaskStatus;
use App\Models\ComputingResource;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Http;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// php8 artisan job:dispatch AbortTaskJob 1

class AbortTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fibonacci_rpc;
    private Task $task;
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

//        $this->rpc_client = new RpcClient();
//
//        // Здесь отправляется так называемый prop-запрос
//        $this->rpc_client->call(json_encode([
//            "method" => "check_task",
//            "params" => $task_array
//        ]));
//        $task_status = json_decode($this->rpc_client->response);

        $response = Http::get('http://localhost:7070/tasks/' . $this->task->id . '/abort');

        // Получить ответ
        $responseData = $response->json();

        $this->task->update(['status'=> TaskStatus::from($responseData["status"]) ]);

        return true;

    }
}
