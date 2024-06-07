<?php

namespace App\GraphQL\Mutations;

use App\Constants\AuthConstants;
use App\Jobs\SendTaskJob;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;

class Logout
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args): ?User
    {
        // Plain Laravel: Auth::guard()
        // Laravel Sanctum: Auth::guard(Arr::first(config('sanctum.guard', 'web')))
        $guard  = Auth::guard();

        $user = $guard->user();

        $user->tokens()->delete();
//        $guard->logout(); // Не срабатывает

        return $user;
    }
}
