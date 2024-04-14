<?php

use Laniakea\DataTables\DataTablesManager;
use Laniakea\DataTables\Interfaces\DataTablesManagerInterface;
use Laniakea\Forms\FormIdsGenerator;
use Laniakea\Forms\FormsManager;
use Laniakea\Forms\Interfaces\FormIdsGeneratorInterface;
use Laniakea\Forms\Interfaces\FormsManagerInterface;
use Laniakea\Resources\Commands\FilterResources;
use Laniakea\Resources\Commands\LoadInclusions;
use Laniakea\Resources\Commands\SortResources;
use Laniakea\Resources\Interfaces\ResourceManagerInterface;
use Laniakea\Resources\ResourceManager;
use Laniakea\Settings\Interfaces\SettingsGeneratorInterface;
use Laniakea\Settings\Interfaces\SettingsUpdaterInterface;
use Laniakea\Settings\Interfaces\SettingsValuesInterface;
use Laniakea\Settings\SettingsGenerator;
use Laniakea\Settings\SettingsUpdater;
use Laniakea\Settings\SettingsValues;

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

    'datatables' => [
        'fields' => [
            'sorting' => 'order_by',
        ],
    ],

    'bindings' => [
        ResourceManagerInterface::class => ResourceManager::class,
        DataTablesManagerInterface::class => DataTablesManager::class,
        FormIdsGeneratorInterface::class => FormIdsGenerator::class,
        FormsManagerInterface::class => FormsManager::class,
        SettingsGeneratorInterface::class => SettingsGenerator::class,
        SettingsUpdaterInterface::class => SettingsUpdater::class,
        SettingsValuesInterface::class => SettingsValues::class,
    ],
];
