<?php

namespace App\GraphQL\Queries;

use App\Enums\TaskStatus;
use App\Jobs\CheckTaskJob;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class Tasks
{
    public function __invoke($_, array $args)
    {
        $tasks = Task::all();

        $tasks_filtered = $tasks;

//        Log:info($args);

        if(isset($args['project_id'])) {
            $tasks_filtered = [];
            foreach ($tasks as $task) {
                if($task->project->id == $args['project_id']) {
                    array_push($tasks_filtered, $task);
                }

            }

        }

        for ($i = 0; $i < count($tasks_filtered); $i++) {

//            $tasks[$i]->jobs =  json_decode($tasks[$i]->jobs);



            if($tasks_filtered[$i]->status != TaskStatus::COMPLETED->name and $tasks_filtered[$i]->status != TaskStatus::DRAFT->name) {
//                Log:info("task: " . $tasks[$i]->status);
                CheckTaskJob::dispatch($tasks_filtered[$i]->id);
            }

        }

        return $tasks_filtered;
    }
}
