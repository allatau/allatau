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
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// php8 artisan job:dispatch CheckTaskJob 1

class CheckTaskJob implements ShouldQueue
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

        try {
            $task_array = array_merge($this->task->getAttributes(), ['computing_resource' => $this->computing_resource->getAttributes()]);

            $response = Http::get('http://localhost:7070/tasks/' . $this->task->id);

            // Получить ответ
            $responseData = $response->json();

            $this->task->update(['status'=> TaskStatus::from($responseData["status"]), 'jobs' => $responseData["jobs"]  ]);

        } catch(\Illuminate\Http\Client\ConnectionException $e) {
            Log::info('Нет соединения с оркестратором');
        }

        return true;
    }
}
