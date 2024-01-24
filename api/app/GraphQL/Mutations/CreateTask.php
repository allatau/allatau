<?php

namespace App\GraphQL\Mutations;

use App\Enums\TaskStatus;
use App\Models\Task;

class CreateTask
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $task = new Task();
        $task->name = $args['name'];
        $task->status = TaskStatus::DRAFT->name;
        $task->files = "{}";
        $task->script = $args['script'] ?? "";
        $task->jobs = $args['script'] ?? "[]";
        $task->computing_resource_id = $args['computing_resource_id'];
        $task->computational_model_resource = $args['computational_model_resource']  ?? "";
        $task->extra = "{}";
        $task->type = "default";
        $task->numerical_model = $args['numerical_model']  ?? "";
        $task->converter_service = $args['converter_service']  ?? "";
        $task->save();

        return $task;
    }
}
