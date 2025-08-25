<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'vendor' => [
            'driver' => 'session',
            'provider' => 'vendors',
        ],
        'manager' => [
            'driver' => 'session',
            'provider' => 'managers',
        ],
        'accountant' => [
            'driver' => 'session',
            'provider' => 'accountants',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'vendors' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'managers' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'accountants' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];