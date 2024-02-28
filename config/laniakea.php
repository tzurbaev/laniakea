<?php

use Laniakea\Forms\FormIdsGenerator;
use Laniakea\Forms\FormsManager;
use Laniakea\Forms\Interfaces\FormIdsGeneratorInterface;
use Laniakea\Forms\Interfaces\FormsManagerInterface;
use Laniakea\Resources\Commands\FilterResources;
use Laniakea\Resources\Commands\LoadInclusions;
use Laniakea\Resources\Commands\SortResources;
use Laniakea\Resources\Interfaces\ResourceManagerInterface;
use Laniakea\Resources\ResourceManager;

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

    'bindings' => [
        ResourceManagerInterface::class => ResourceManager::class,
        FormIdsGeneratorInterface::class => FormIdsGenerator::class,
        FormsManagerInterface::class => FormsManager::class,
    ],
];
