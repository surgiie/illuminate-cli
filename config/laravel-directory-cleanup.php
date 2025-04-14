<?php

use App\Policies\DirectoryCleanup\DefaultCleanupPolicy;

return [

    'directories' => [

        /*
         * Here you can specify which directories need to be cleanup. All files older than
         * the specified amount of minutes will be deleted.
         */

        illuminate_cli_workspace_path('compiled-views', 'ILLUMINATE_CLI_VIEW_COMPILED_PATH') => [
            'deleteAllOlderThanMinutes' => 60 * 24,
        ],

    ],

    /*
     * The policy class that determines what is deleted.
     */
    'cleanup_policy' => DefaultCleanupPolicy::class,
];
