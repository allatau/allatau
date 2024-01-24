<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SendController;
use App\Http\Controllers\RabbitMQRecieveController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user/{id}', [UserController::class, 'show']);

Route::get('/send', [SendController::class, 'sendText']);
Route::get('/rabbitmq-receiver', [RabbitMQRecieveController::class, 'sendJob']);
