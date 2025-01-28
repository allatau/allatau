<?php

namespace App\GraphQL\Mutations;

use App\Constants\AuthConstants;
use App\Jobs\SendTaskJob;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use GraphQL\Error\Error;


class Login
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {

        $credentials = [
            'email' => $args['email'],
            'password' => $args['password']
        ];

        if( ! auth()->attempt($credentials)) {
            throw new Error('Invalid credentials.');
        }

        $user = auth()->user();

        $user->tokens()->delete();

        $success = $user->createToken('MyApp')->plainTextToken;


        return ['token' => $success];

    }
}
