<?php

namespace App\Http\Controllers;

use App\Jobs\TestJob;
use Hug\Sftp\Sftp as Sftp;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Storage;

// https://laravel.su/docs/8.x/filesystem?ysclid=l97indhhj2212086983#downloading-files
// https://hackthestuff.com/article/laravel-8-file-upload-and-download-example
// https://dev.to/codeanddeploy/laravel-8-file-upload-example-chk
// https://laratutorials.com/laravel-8-file-upload-via-api/
class ArchiveFileController extends Controller
{
    function getFile($guid){
        $test = Sftp::download('localhost', "sshuser", "123", '/home/sshuser/calculations/32e9f38e-5755-4d4b-aab6-468244379409/archive.tar.gz', 'storage/app/public/archives/32e9f38e-5755-4d4b-aab6-468244379409.tar.gz', $port = 2022);

        return Storage::download('public/archives/'.$guid.".tar.gz");
    }


    public function index()
    {
        echo "test";
    }
}
