<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Controls the HTTP route that your GraphQL server responds to.
    | You may set `route` => false, to disable the default route
    | registration and take full control.
    |
    */
    'route' => [

        /*
         * Beware that middleware defined here runs before the GraphQL execution phase,
         * make sure to return spec-compliant responses in case an error is thrown.
         */
        'middleware' => [

            // Middleware для авторизации
//             \App\Http\Middleware\ApiAuthenticate::class,

        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ],

    ],
    'guards' => ['sanctum'],
//    'middleware' => [
//        'auth.api',
//    ],
//    'guards' => ['api'],

];
