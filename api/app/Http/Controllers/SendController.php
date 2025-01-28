<?php

namespace App\Http\Controllers;

use App\Jobs\TestJob;
use Illuminate\Http\Request;

class SendController extends Controller
{
    public function sendText()
    {
//        SendTextJob::dispatch()->onQueue('text');
        TestJob::dispatch('test');
        echo "test";
    }
}
