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
use Hug\Sftp\Sftp as Sftp;
use Illuminate\Support\Facades\Log;

class DownloadArchiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // php artisan job:dispatch DownloadArchiveJob <guid>
    protected Task $task;
    protected ComputingResource $computing_resource;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($task_id)
    {
        $this->task = Task::find($task_id);
        $this->computing_resource = ComputingResource::find($this->task->computing_resource_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Sftp::download($this->computing_resource->host, $this->computing_resource->login, $this->computing_resource->password, '/home/'.$this->computing_resource->login.'/calculations/'.$this->task->id.'/archive.tar.gz', storage_path('app/public/archives/'.$this->task->id.'.tar.gz'), $port = $this->computing_resource->port, $timeout = 10);
    }
}
