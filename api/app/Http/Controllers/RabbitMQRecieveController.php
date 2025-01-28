<?php

namespace App\Http\Controllers;

use App\Jobs\SendTaskJob;
use App\Jobs\TestJob;
use Illuminate\Http\Request;

class RabbitMQRecieveController extends Controller
{
    public function sendJob()
    {
        SendTaskJob::dispatch();
        echo "RabbitMQ message receiver is working";
    }
}
