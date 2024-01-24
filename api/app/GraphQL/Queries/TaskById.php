<?php

namespace App\GraphQL\Queries;

use App\Enums\TaskStatus;
use App\Jobs\CheckTaskJob;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskById
{
    public function __invoke($_, array $args)
    {
        $tasks = [Task::find($args['id'])];

        for ($i = 0; $i < count($tasks); $i++) {

            if($tasks[$i]->status != TaskStatus::COMPLETED->name and $tasks[$i]->status != TaskStatus::DRAFT->name) {

                CheckTaskJob::dispatch($tasks[$i]->id);
            }

        }

        return $tasks[0];
    }
}
