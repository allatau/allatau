<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\API\FileUploadController;
use App\Http\Controllers\ArchiveFileController;
use App\Http\Controllers\AllFileController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('view', [FileController::class, 'index']);
Route::get('/public/cases/{filename}', [FileController::class, 'getfile']);
Route::get('/public/archives/{filename}', [ArchiveFileController::class, 'getfile']);
Route::post('/uploading-file-api', [FileUploadController::class, 'upload']);
Route::get('/public/files/{guid}', [AllFileController::class, 'getfile']);
