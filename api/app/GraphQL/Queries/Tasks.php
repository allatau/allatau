<?php

namespace App\GraphQL\Queries;

use App\Enums\TaskStatus;
use App\Jobs\CheckTaskJob;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class Tasks
{
    public function __invoke()
    {
        $tasks = Task::all();

        for ($i = 0; $i < count($tasks); $i++) {

//            $tasks[$i]->jobs =  json_decode($tasks[$i]->jobs);

            if($tasks[$i]->status != TaskStatus::COMPLETED->name and $tasks[$i]->status != TaskStatus::DRAFT->name) {
                Log:info("task: " . $tasks[$i]->status);
                CheckTaskJob::dispatch($tasks[$i]->id);
            }

        }

        return $tasks;
    }
}
