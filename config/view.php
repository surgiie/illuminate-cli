<?php

return [
    'paths' => [], // paths are computed at runtime
    'cache' => env('ILLUMINATE_CLI_VIEW_CACHE', true),
    'compiled' => illuminate_cli_workspace_path('compiled-views', 'ILLUMINATE_CLI_VIEW_COMPILED_PATH'),
];
