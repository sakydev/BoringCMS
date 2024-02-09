<?php

use Sakydev\Boring\Models\BoringUser;

return [
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => BoringUser::class,
        ],
    ],
];
