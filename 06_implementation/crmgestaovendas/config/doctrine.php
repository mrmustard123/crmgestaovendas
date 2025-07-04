<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Entity Managers
    |--------------------------------------------------------------------------
    |
    | Configure your Entity Managers here. You can set a different connection
    | and driver per manager and configure events and filters. Change the
    | paths setting to the appropriate path and replace App namespace
    | by your own namespace.
    |
    | Available meta drivers: attributes|xml|simplified_xml|static_php|php
    |
    | Available connections: mysql|oracle|pgsql|sqlite|sqlsrv
    | (Connections can be configured in the database config)
    |
    | Depending on the chosen database connection, various other settings are
    | available. Check the available settings for your connection type in
    | the LaravelDoctrine\ORM\Configuration\Connections namespace.
    |
    | --> Warning: Proxy auto generation should only be enabled in dev!
    |
    */
    'managers'                   => [
        'default' => [
            'dev'           => env('APP_DEBUG', false),
            'meta'          => env('DOCTRINE_METADATA', 'attributes'),
            'connection'    => env('DB_CONNECTION', 'mysql'),
            'paths'         => [
                base_path('app/Models/Doctrine')
            ],

            'repository'    => Doctrine\ORM\EntityRepository::class,

            'proxies'       => [
                'namespace'     => 'DoctrineProxies',
                'path'          => storage_path('proxies'),
                'auto_generate' => env('DOCTRINE_PROXY_AUTOGENERATE', false)
            ],

            /*
            |--------------------------------------------------------------------------
            | Doctrine events
            |--------------------------------------------------------------------------
            |
            | The listener array expects the key to be a Doctrine event
            | e.g. Doctrine\ORM\Events::onFlush
            |
            */
            'events'        => [
                'listeners'   => [],
                'subscribers' => []
            ],

            'filters'       => [],

            /*
            |--------------------------------------------------------------------------
            | Doctrine mapping types
            |--------------------------------------------------------------------------
            |
            | Link a Database Type to a Local Doctrine Type
            |
            | Using 'enum' => 'string' is the same of:
            | $doctrineManager->extendAll(function (\Doctrine\ORM\Configuration $configuration,
            |         \Doctrine\DBAL\Connection $connection,
            |         \Doctrine\Common\EventManager $eventManager) {
            |     $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
            | });
            |
            | References:
            | https://www.doctrine-project.org/projects/doctrine-orm/en/current/cookbook/custom-mapping-types.html
            | https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html#custom-mapping-types
            | https://www.doctrine-project.org/projects/doctrine-orm/en/current/cookbook/advanced-field-value-conversion-using-custom-mapping-types.html
            | https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/basic-mapping.html
            | https://symfony.com/doc/current/doctrine/dbal.html#registering-custom-mapping-types-in-the-schematool
            |--------------------------------------------------------------------------
            */
            'mapping_types' => [
                //'enum' => 'string'
            ],

            /**
             * References:
             * https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/architecture.html#middlewares
             */
            'middlewares' => [
                // Doctrine\DBAL\Logging\Middleware::class
            ]
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine Extensions
    |--------------------------------------------------------------------------
    |
    | Enable/disable Doctrine Extensions by adding or removing them from the list
    |
    | If you want to require custom extensions you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'extensions'                 => [
        //LaravelDoctrine\Extensions\Timestamps\TimestampableExtension::class,
        //LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension::class,
        //LaravelDoctrine\Extensions\Sluggable\SluggableExtension::class,
        //LaravelDoctrine\Extensions\Sortable\SortableExtension::class,
        //LaravelDoctrine\Extensions\Tree\TreeExtension::class,
        //LaravelDoctrine\Extensions\Loggable\LoggableExtension::class,
        //LaravelDoctrine\Extensions\Blameable\BlameableExtension::class,
        //LaravelDoctrine\Extensions\IpTraceable\IpTraceableExtension::class,
        //LaravelDoctrine\Extensions\Translatable\TranslatableExtension::class
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine custom types
    |--------------------------------------------------------------------------
    |
    | Create a custom or override a Doctrine Type
    |--------------------------------------------------------------------------
    */
    'custom_types'               => [
    ],
    /*
    |--------------------------------------------------------------------------
    | DQL custom datetime functions
    |--------------------------------------------------------------------------
    */
    'custom_datetime_functions'  => [],
    /*
    |--------------------------------------------------------------------------
    | DQL custom numeric functions
    |--------------------------------------------------------------------------
    */
    'custom_numeric_functions'   => [],
    /*
    |--------------------------------------------------------------------------
    | DQL custom string functions
    |--------------------------------------------------------------------------
    */
    'custom_string_functions'    => [],
    /*
    |--------------------------------------------------------------------------
    | Register custom hydrators
    |--------------------------------------------------------------------------
    */
    'custom_hydration_modes'     => [
        // e.g. 'hydrationModeName' => MyHydrator::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configure meta-data, query and result caching here.
    | Optionally you can enable second level caching.
    |
    | Available: apc|array|file|illuminate|memcached|php_file|redis
    |
    */
    'cache' => [
        'second_level'     => false,
        'default'          => env('DOCTRINE_CACHE', 'array'),
        'namespace'        => null,
        'metadata'         => [
            'driver'       => env('DOCTRINE_METADATA_CACHE', env('DOCTRINE_CACHE', 'array')),
            'namespace'    => 'metadata',
        ],
        'query'            => [
            'driver'       => env('DOCTRINE_QUERY_CACHE', env('DOCTRINE_CACHE', 'array')),
            'namespace'    => 'query',
        ],
        'result'           => [
            'driver'       => env('DOCTRINE_RESULT_CACHE', env('DOCTRINE_CACHE', 'array')),
            'namespace'    => 'result',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Gedmo extensions
    |--------------------------------------------------------------------------
    |
    | Settings for Gedmo extensions
    | If you want to use this you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'gedmo'                      => [
        'all_mappings' => false
    ],
    /*
     |--------------------------------------------------------------------------
     | Validation
     |--------------------------------------------------------------------------
     |
     |  Enables the Doctrine Presence Verifier for Validation
     |
     */
    'doctrine_presence_verifier' => true,

    /*
     |--------------------------------------------------------------------------
     | Notifications
     |--------------------------------------------------------------------------
     |
     |  Doctrine notifications channel
     |
     */
    'notifications'              => [
        'channel' => 'database'
    ]
];
