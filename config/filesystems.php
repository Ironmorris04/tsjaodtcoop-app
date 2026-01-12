<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | The default disk to use. Use 'local' for development and 's3' for
    | production (e.g., Render). Set in your .env file.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Configure multiple disks here. You have local disks for private, public,
    | backups, and a cloud disk for S3. You can continue to use your folders
    | exactly as you have them.
    |
    */

    'disks' => [

        // Local private files
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        // Local public files
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        // Dedicated local backups folder
        'backups' => [
            'driver' => 'local',
            'root' => storage_path('app/backups'),
            'throw' => false,
            'report' => false,
        ],

        // Cloud storage (S3)
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => true,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | These links are created when running `php artisan storage:link`.
    | Your local 'public' storage will be linked to public/storage.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
