<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Octane Server
    |--------------------------------------------------------------------------
    |
    | This value determines which server will be used by Octane. The resolved
    | server instance will be used to start and manage the application server.
    | You are free to switch between them as they are interchangeable.
    |
    | Supported: "roadrunner", "swoole", "frankenphp"
    |
    */

    'server' => env('OCTANE_SERVER', 'roadrunner'),

    /*
    |--------------------------------------------------------------------------
    | Server Port
    |--------------------------------------------------------------------------
    |
    | This value is the port at which the Octane server will listen for
    | requests. This value may be any port on your machine, however all
    | traffic to the application will need to be routed to this port.
    |
    */

    'port' => env('OCTANE_PORT', 8000),

    /*
    |--------------------------------------------------------------------------
    | Octane Workers
    |--------------------------------------------------------------------------
    |
    | This value determines the number of "workers" that will serve your
    | Octane application. Each worker will serve incoming requests. You
    | should generally set this value to the number of processing cores.
    |
    */

    'workers' => env('OCTANE_WORKERS', 4),

    /*
    |--------------------------------------------------------------------------
    | Max Requests
    |--------------------------------------------------------------------------
    |
    | The number of requests an Octane worker will process before being
    | restarted to prevent memory leaks. This is very useful to ensure
    | that long running servers do not accumulate deteriorated app state.
    |
    */

    'max_requests' => env('OCTANE_MAX_REQUESTS', 500),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be applied to every request served by Octane
    | and are in addition to the middleware specified in your "bootstrap"
    | configuration. Feel free to add any additional middleware you need.
    |
    */

    'middleware' => [
        \Illuminate\Http\Middleware\HandleCors::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Warm Cache
    |--------------------------------------------------------------------------
    |
    | Enabling this option will cause Octane to warm the application
    | cache on boot. This is useful for warming any caches you may
    | have configured to help speed up your application requests.
    |
    */

    'warm' => false,

    /*
    |--------------------------------------------------------------------------
    | Listeners
    |--------------------------------------------------------------------------
    |
    | The following array lists all of the listeners that will be registered
    | with Octane. These listeners will be triggered during the server's
    | operation and may perform various tasks as needed by your app.
    |
    */

    'listeners' => [
        'start' => [
            \App\Octane\OptimizeForOctane::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tick Handlers
    |--------------------------------------------------------------------------
    |
    | The following array lists all of the "tick" handlers that will be
    | registered with Octane. These handlers will be called on every
    | server "tick" and should return as quickly as possible.
    |
    */

    'tick_handlers' => [
        \App\Octane\MonitorMemory::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Flush Handlers
    |--------------------------------------------------------------------------
    |
    | The following array lists all of the "flush" handlers that will be
    | registered with Octane. These handlers will be called when you
    | request the Octane server be flushed from the terminal.
    |
    */

    'flush_handlers' => [
        // App\Octane\FlushHandlers\FlushDatabase::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | RoadRunner Configuration
    |--------------------------------------------------------------------------
    |
    | The following array contains the configuration options that will be
    | used to configure RoadRunner. These options will be placed in the
    | ".rr.yaml" file that is placed in your application root.
    |
    */

    'roadrunner' => [
        'config' => [
            'server' => [
                'command' => 'php artisan octane:start',
                'relay' => 'tcp://127.0.0.1:6001',
                'relay_timeout' => '30s',
            ],
            'http' => [
                'address' => '0.0.0.0:8000',
                'max_request_size' => '33554432', // 32 MB
                'middleware' => [
                    'timeout',
                    'gzip',
                ],
                'pool' => [
                    'num_workers' => env('OCTANE_WORKERS', 4),
                    'max_jobs' => env('OCTANE_MAX_REQUESTS', 500),
                    'allocate_timeout' => '60s',
                    'destroy_timeout' => '60s',
                ],
            ],
            'logs' => [
                'level' => env('OCTANE_LOG_LEVEL', 'error'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Swoole Configuration
    |--------------------------------------------------------------------------
    |
    | The following array contains the configuration options that will be
    | used to configure Swoole. These options will be provided to the
    | Swoole server when it is started by Octane.
    |
    */

    'swoole' => [
        'options' => [
            'worker_num' => env('OCTANE_WORKERS', 4),
            'task_worker_num' => env('OCTANE_TASK_WORKERS', 6),
            'max_request' => env('OCTANE_MAX_REQUESTS', 500),
            'max_conn' => 100,
            'max_coroutine' => 200,
            'memory_limit' => '512M',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | FrankenPHP Configuration
    |--------------------------------------------------------------------------
    |
    | The following array contains the configuration options that will be
    | used to configure FrankenPHP. These options will be provided to
    | FrankenPHP when it is started by Octane.
    |
    */

    'frankenphp' => [
        'options' => [
            'num_workers' => env('OCTANE_WORKERS', 4),
        ],
    ],
];
