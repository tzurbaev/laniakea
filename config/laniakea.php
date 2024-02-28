<?php

use Laniakea\Resources\Commands\FilterResources;
use Laniakea\Resources\Commands\LoadInclusions;
use Laniakea\Resources\Commands\SortResources;

return [
    'registrars' => [
        // List your resource registrars here
    ],

    'resources' => [
        'fields' => [
            'count' => 'count',
            'page' => 'page',
            'inclusions' => 'with',
            'sorting' => 'order_by',
        ],

        'commands' => [
            'pagination' => [
                FilterResources::class,
                LoadInclusions::class,
                SortResources::class,
            ],

            'list' => [
                FilterResources::class,
                LoadInclusions::class,
                SortResources::class,
            ],

            'item' => [
                LoadInclusions::class,
            ],
        ],
    ],
];
