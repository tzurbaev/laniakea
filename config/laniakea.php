<?php

return [
    /**
     * Registrars are classes that are responsible for registering your application resources.
     * They register your route bindings and versioned services.
     *
     * https://laniakea.zurbaev.com/resources/registrars.html
     */
    'registrars' => [
        // List your resource registrars here
    ],

    'resources' => [
        /**
         * In this section you can override query parameters that would be used to filter,
         * sort and paginate your resources.
         *
         * For example, when you request a list of resources, you can specify the number of resources
         * with the `count` query parameter. If you want to change it to `limit`, update the value of the `count` key.
         *
         * https://laniakea.zurbaev.com/configuration.html#resource-request-fields
         */
        'fields' => [
            /**
             * The `count` field is used to specify the number of resources to return.
             */
            'count' => 'count',

            /**
             * The `page` field is used to specify the page number of the resources to return.
             */
            'page' => 'page',

            /**
             * The `with` field is used to specify the relationships to include in the response.
             */
            'inclusions' => 'with',

            /**
             * The `order_by` field is used to specify the sorting order of the resources.
             */
            'sorting' => 'order_by',
        ],

        /**
         * Resource commands are special classes that perform actions on your resources depending on operation type.
         *
         * By default, when you paginate or list resources using the Resource Manager, it will filter, sort, and
         * include relationships in the response. When you load item by ID, it will only include relationships.
         *
         * You can add your own commands to each operation type. Each command must implement
         * the `Laniakea\Resources\Interfaces\ResourceManagerCommandInterface` interface.
         *
         * https://laniakea.zurbaev.com/configuration.html#resource-commands
         */
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

    /**
     * Transformers are classes that turns your Eloquent models into JSON responses.
     */
    'transformers' => [
        /**
         * While you can specify a serializer for each transformation, you can also set a default one.
         * This serializer will be used when no serializer is specified.
         *
         * If you want to disable default serializer for a specific transformation,
         * use TransformationManager::withoutDefaultSerializer() method.
         *
         * Serializer must implement the Laniakea\Transformers\Interfaces\TransformationSerializerInterface interface.
         */
        'default_serializer' => null,
    ],

    /**
     * Those are default bindings for the Laniakea managers. If you're not happy with default ones,
     * you can make your own implementations and bind them here.
     *
     * https://laniakea.zurbaev.com/configuration.html#bindings
     */
    'bindings' => [
        Laniakea\Resources\Interfaces\ResourceManagerInterface::class => Laniakea\Resources\ResourceManager::class,
        Laniakea\Forms\Interfaces\FormIdsGeneratorInterface::class => Laniakea\Forms\FormIdsGenerator::class,
        Laniakea\Forms\Interfaces\FormsManagerInterface::class => Laniakea\Forms\FormsManager::class,
        Laniakea\Settings\Interfaces\SettingsGeneratorInterface::class => Laniakea\Settings\SettingsGenerator::class,
        Laniakea\Settings\Interfaces\SettingsUpdaterInterface::class => Laniakea\Settings\SettingsUpdater::class,
        Laniakea\Settings\Interfaces\SettingsValuesInterface::class => Laniakea\Settings\SettingsValues::class,
    ],
];
