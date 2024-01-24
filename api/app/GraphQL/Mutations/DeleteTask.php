<?php

namespace App\GraphQL\Mutations;

use App\Models\Task;

class DeleteTask
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
         $task = Task::find($args['id']);
         $task->delete();

        return $task;
    }
}
