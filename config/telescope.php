<?php

use DijDigital\TelescopeMongoDb\Watchers\CacheWatcher;
use DijDigital\TelescopeMongoDb\Watchers\ClientRequestWatcher;
use DijDigital\TelescopeMongoDb\Watchers\CommandWatcher;
use DijDigital\TelescopeMongoDb\Watchers\DumpWatcher;
use DijDigital\TelescopeMongoDb\Watchers\EventWatcher;
use DijDigital\TelescopeMongoDb\Watchers\ExceptionWatcher;
use DijDigital\TelescopeMongoDb\Watchers\GateWatcher;
use DijDigital\TelescopeMongoDb\Watchers\JobWatcher;
use DijDigital\TelescopeMongoDb\Watchers\LogWatcher;
use DijDigital\TelescopeMongoDb\Watchers\MailWatcher;
use DijDigital\TelescopeMongoDb\Watchers\ModelWatcher;
use DijDigital\TelescopeMongoDb\Watchers\NotificationWatcher;
use DijDigital\TelescopeMongoDb\Watchers\QueryWatcher;
use DijDigital\TelescopeMongoDb\Watchers\RedisWatcher;
use DijDigital\TelescopeMongoDb\Watchers\RequestWatcher;
use DijDigital\TelescopeMongoDb\Watchers\ScheduleWatcher;
use DijDigital\TelescopeMongoDb\Watchers\ViewWatcher;

return [
    'enabled' => env('TELESCOPE_ENABLED', env('APP_DEBUG', false)),
    'domain' => env('TELESCOPE_DOMAIN'),
    'path' => env('TELESCOPE_PATH', 'telescope'),
    'driver' => env('TELESCOPE_DRIVER', 'mongodb'),

    'storage' => [
        'database' => [
            'connection' => env('TELESCOPE_DB_CONNECTION', 'mongodb'),
            'chunk' => 1000,
        ],
    ],

    'queue' => [
        'connection' => env('TELESCOPE_QUEUE_CONNECTION'),
        'queue' => env('TELESCOPE_QUEUE'),
        'delay' => env('TELESCOPE_QUEUE_DELAY', 10),
    ],

    'middleware' => [
        'web',
        DijDigital\TelescopeMongoDb\Http\Middleware\Authorize::class,
    ],

    'ignore_paths' => [
        'livewire*',
        'nova-api*',
        'pulse*',
        '_boost*',
        '.well-known*',
        'telescope*',
        'up',
    ],

    'watchers' => [
        CacheWatcher::class => [
            'enabled' => env('TELESCOPE_CACHE_WATCHER', true),
            'hidden' => [],
            'ignore' => [],
        ],

        ClientRequestWatcher::class => [
            'enabled' => env('TELESCOPE_CLIENT_REQUEST_WATCHER', true),
            'ignore_hosts' => [],
        ],

        CommandWatcher::class => [
            'enabled' => env('TELESCOPE_COMMAND_WATCHER', true),
            'ignore' => [],
        ],

        DumpWatcher::class => [
            'enabled' => env('TELESCOPE_DUMP_WATCHER', true),
            'always' => env('TELESCOPE_DUMP_WATCHER_ALWAYS', false),
        ],

        EventWatcher::class => [
            'enabled' => env('TELESCOPE_EVENT_WATCHER', true),
            'ignore' => [],
        ],

        ExceptionWatcher::class => env('TELESCOPE_EXCEPTION_WATCHER', true),

        GateWatcher::class => [
            'enabled' => env('TELESCOPE_GATE_WATCHER', true),
            'ignore_abilities' => [],
            'ignore_packages' => true,
            'ignore_paths' => [],
        ],

        JobWatcher::class => env('TELESCOPE_JOB_WATCHER', true),

        LogWatcher::class => [
            'enabled' => env('TELESCOPE_LOG_WATCHER', true),
            'level' => 'error',
        ],

        MailWatcher::class => env('TELESCOPE_MAIL_WATCHER', true),

        ModelWatcher::class => [
            'enabled' => env('TELESCOPE_MODEL_WATCHER', true),
            'events' => ['eloquent.*'],
            'hydrations' => true,
        ],

        NotificationWatcher::class => env('TELESCOPE_NOTIFICATION_WATCHER', true),

        QueryWatcher::class => [
            'enabled' => env('TELESCOPE_QUERY_WATCHER', true),
            'ignore_packages' => true,
            'ignore_paths' => [],
            'slow' => 100,
        ],

        RedisWatcher::class => env('TELESCOPE_REDIS_WATCHER', true),

        RequestWatcher::class => [
            'enabled' => env('TELESCOPE_REQUEST_WATCHER', true),
            'size_limit' => env('TELESCOPE_RESPONSE_SIZE_LIMIT', 64),
            'ignore_http_methods' => [],
            'ignore_status_codes' => [],
        ],

        ScheduleWatcher::class => env('TELESCOPE_SCHEDULE_WATCHER', true),
        ViewWatcher::class => env('TELESCOPE_VIEW_WATCHER', true),
    ],
];
