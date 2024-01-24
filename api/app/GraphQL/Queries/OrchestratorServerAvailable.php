<?php

namespace App\GraphQL\Queries;

use App\Enums\ComputingResourceStatus;
use App\Enums\TaskStatus;
use App\Models\ResourceStatus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrchestratorServerAvailable
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $status = ComputingResourceStatus::OFFLINE;
        try {
            $response = Http::get('http://localhost:7070/tasks/');
            $status = ComputingResourceStatus::ONLINE;
        } catch(\Illuminate\Http\Client\ConnectionException $e) {
            Log::info('Нет соединения с оркестратором');
        }

        $r = new ResourceStatus();
        $r->status = $status->name;

        return $r;
    }
}
