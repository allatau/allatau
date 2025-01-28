<?php

namespace App\Http\Controllers;

use App\Jobs\TestJob;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Storage;

// https://laravel.su/docs/8.x/filesystem?ysclid=l97indhhj2212086983#downloading-files
// https://hackthestuff.com/article/laravel-8-file-upload-and-download-example
// https://dev.to/codeanddeploy/laravel-8-file-upload-example-chk
// https://laratutorials.com/laravel-8-file-upload-via-api/
class FileController extends Controller
{
    function getFile($filename){
        return Storage::download('public/cases/'.$filename);
    }


    public function index()
    {
        echo "test";
    }
}
