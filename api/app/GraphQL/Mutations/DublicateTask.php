<?php

namespace App\GraphQL\Mutations;

use App\Enums\TaskStatus;
use App\Models\Task;

class DublicateTask
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
         $task_old = Task::find($args['id']);
         $task = $task_old->replicate();
         $task->status = TaskStatus::DRAFT->name;
         $task->name = $task_old->name . " (Copy)";
         $task->jobs = "[]";
         $task->push();

        return $task;
    }
}
