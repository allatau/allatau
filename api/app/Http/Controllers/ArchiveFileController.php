<?php

namespace App\Http\Controllers;

use App\Jobs\TestJob;
use Hug\Sftp\Sftp as Sftp;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Storage;
use App\Jobs\DownloadArchiveJob;

// https://laravel.su/docs/8.x/filesystem?ysclid=l97indhhj2212086983#downloading-files
// https://hackthestuff.com/article/laravel-8-file-upload-and-download-example
// https://dev.to/codeanddeploy/laravel-8-file-upload-example-chk
// https://laratutorials.com/laravel-8-file-upload-via-api/
class ArchiveFileController extends Controller
{
    function getFile($guid){

//        DownloadArchiveJob::dispatch($guid);
//        sleep(10);
        return Storage::download('public/archives/'.$guid.".tar.gz");
    }


    public function index()
    {
        echo "test";
    }
}
