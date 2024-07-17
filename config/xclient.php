<?php

// config for JOOservices/XClient
return [
    'log_path' => env('XCLIENT_LOG_PATH', storage_path('logs/xclient.log')),
    'log_driver' => env('XCLIENT_LOG_DRIVER', 'daily'),
    'cache_interval' => env('XCLIENT_CACHE_INTERVAL', 3600),
];
