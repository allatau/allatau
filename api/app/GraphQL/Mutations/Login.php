<?php

namespace App\GraphQL\Mutations;

use App\Constants\AuthConstants;
use App\Jobs\SendTaskJob;
use App\Models\Task;
use Illuminate\Support\Facades\Log;


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

        if (auth()->attempt($credentials)) {
            Log::info($args["email"]);


            $user = auth()->user();

            $user->tokens()->delete();

            $success = $user->createToken('MyApp')->plainTextToken;

//            Log::info(['token' => $success]);

            return ['token' => $success];
//
//            return $this->success(['token' => $success], AuthConstants::LOGIN);
        }

//        $item = [
//            "token" => "hello_world"
//        ];
//
//
//        return $item;
    }
}
