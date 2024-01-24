<?php

namespace App\GraphQL\Mutations;

use App\Jobs\CheckTaskJob;
use App\Models\Task;

class CheckTask
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        CheckTaskJob::dispatch($args['id']);

        $task = Task::find($args['id']);

        return $task ;
    }
}
