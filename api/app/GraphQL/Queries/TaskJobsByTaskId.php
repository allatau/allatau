<?php

namespace App\GraphQL\Queries;

use App\Enums\TaskStatus;
use App\Jobs\CheckTaskJob;
use App\Models\ResourceStatus;
use App\Models\Task;
use App\Models\TaskJobDetails;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Nette\Utils\ArrayList;
use function Sodium\add;

class TaskJobsByTaskId
{
    public function __invoke($_, array $args)
    {
        $task_jobs = [];
        $task = new Task();
        $task->jobs = [];
        $task = Task::find($args['id']);

        foreach ($task->jobs as $key => $job_id) {
            // $arr[3] will be updated with each value from $arr...

            Log::info($job_id);


            try {
                $response = Http::get('http://localhost:7000/api/jobs/' . $job_id);

                // Получить ответ
//                $responseData = $response->json();

                $job_details = new TaskJobDetails();
                $job_details->id = $job_id;
                $job_details->json_data = $response;


                array_push($task_jobs, $job_details);


            } catch(\Illuminate\Http\Client\ConnectionException $e) {
                Log::info('Нет соединения с оркестратором');
            }

        }




        return $task_jobs;
    }
}
