<?php

namespace App\GraphQL\Mutations;

use App\Jobs\AbortTaskJob;
use App\Models\Task;

class AbortTask
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        AbortTaskJob::dispatch($args['id']);

        $task = Task::find($args['id']);

        return $task ;
    }
}
