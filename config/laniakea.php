<?php

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
                Laniakea\Resources\Commands\FilterResources::class,
                Laniakea\Resources\Commands\LoadInclusions::class,
                Laniakea\Resources\Commands\SortResources::class,
            ],

            'list' => [
                Laniakea\Resources\Commands\FilterResources::class,
                Laniakea\Resources\Commands\LoadInclusions::class,
                Laniakea\Resources\Commands\SortResources::class,
            ],

            'item' => [
                Laniakea\Resources\Commands\LoadInclusions::class,
            ],
        ],
    ],

    'bindings' => [
        Laniakea\Resources\Interfaces\ResourceManagerInterface::class => Laniakea\Resources\ResourceManager::class,
        Laniakea\Forms\Interfaces\FormIdsGeneratorInterface::class => Laniakea\Forms\FormIdsGenerator::class,
        Laniakea\Forms\Interfaces\FormsManagerInterface::class => Laniakea\Forms\FormsManager::class,
        Laniakea\Settings\Interfaces\SettingsGeneratorInterface::class => Laniakea\Settings\SettingsGenerator::class,
        Laniakea\Settings\Interfaces\SettingsUpdaterInterface::class => Laniakea\Settings\SettingsUpdater::class,
        Laniakea\Settings\Interfaces\SettingsValuesInterface::class => Laniakea\Settings\SettingsValues::class,
    ],
];
