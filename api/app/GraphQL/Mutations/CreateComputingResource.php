<?php

namespace App\GraphQL\Mutations;

use App\Jobs\TestJob;
use App\Jobs\SendTextJob;
use App\Models\ComputingResource;
use Illuminate\Queue\Jobs\Job;
use PHPUnit\Util\Test;

class CreateComputingResource
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $computingResource = ComputingResource::factory()->create();
        $computingResource->name = $args['name'];
        $computingResource->port = $args['port'];
        $computingResource->host = $args['host'];
        $computingResource->login = $args['login'];
        $computingResource->password = $args['password'];
        $computingResource->save();

        return $computingResource;
    }
}
