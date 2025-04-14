<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'name' => file_get_contents(base_path('logo.txt')),

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | This value determines the "version" your application is currently running
    | in. You may want to follow the "Semantic Versioning" - Given a version
    | number MAJOR.MINOR.PATCH when an update happens: https://semver.org.
    |
    */

    'version' => app('git.version'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. This can be overridden using
    | the global command line "--env" option when calling commands.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
     */

    'providers' => [
        // laravel zero implicity loads service providers, by adding them to this list
        // they get shifted to the top of the array, and loaded first, allowing
        // our custom providers to override them.
        LaravelZero\Framework\Components\View\Provider::class,
        // application service providers...
        App\Providers\AppServiceProvider::class,
        App\Providers\ViewServiceProvider::class,
        App\Providers\DuskServiceProvider::class,
        Spatie\DirectoryCleanup\DirectoryCleanupServiceProvider::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */
    'cipher' => env('ILLUMINATE_CLI_CIPHER', 'AES-256-CBC'),
    'key' => env('ILLUMINATE_CLI_ENCRYPTION_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', env('ILLUMINATE_CLI_PREVIOUS_ENCRYPTION_KEYS', ''))
        ),
    ],

];
