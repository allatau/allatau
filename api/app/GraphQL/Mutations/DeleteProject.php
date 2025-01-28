<?php

namespace App\GraphQL\Mutations;

use App\Models\Project;

class DeleteProject
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
         $task = Project::find($args['id']);
         $task->delete();

        return $task;
    }
}
