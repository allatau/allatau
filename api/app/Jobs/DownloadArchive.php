<?php

namespace App\Jobs;

use App\Enums\TaskStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Hug\Sftp\Sftp as Sftp;
class DownloadArchive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        echo TaskStatus::from("DRAFT")->name;
        Sftp::download('localhost', "sshuser", "123", '/home/sshuser/calculations/32e9f38e-5755-4d4b-aab6-468244379409/archive.tar.gz', 'storage/app/public/archives/32e9f38e-5755-4d4b-aab6-468244379409.tar.gz', $port = 2022, $timeout = 10);

    }
}
