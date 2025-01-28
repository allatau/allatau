<?php

namespace App\GraphQL\Mutations;

use App\Jobs\DownloadArchiveJob;
use App\Models\Task;

class PrepareArchive
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        DownloadArchiveJob::dispatch($args['id']);

        return $args['id'];
    }
}
