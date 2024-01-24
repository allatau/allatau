<?php

namespace App\GraphQL\Mutations;

use App\Jobs\SendTaskJob;
use App\Models\Task;

class StartTask
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        SendTaskJob::dispatch($args['id']);

        $task = Task::find($args['id']);

        return $task ;
    }
}
