<?php

namespace App\GraphQL\Mutations;

use App\Enums\TaskStatus;
use App\Models\Project;

class CreateProject
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $task = new Project();
        $task->title = $args['title'];
        $task->description = $args['description'];
        $task->save();

        return $task;
    }
}
