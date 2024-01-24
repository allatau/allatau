<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Log;

class Hello
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
//        echo "Hello running";

        Log::info('This is an informational message.');
        // TODO implement the resolver

        return "Hello";
    }
}
