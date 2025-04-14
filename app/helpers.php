<?php

if (! function_exists('illuminate_cli_workspace_path')) {
    /**
     * Compute a path to a temporary workspace for the CLI depending cli context.
     */
    function illuminate_cli_workspace_path(string $path, ?string $envName = null): string
    {
        if ($env = env($envName)) {
            return $env;
        }

        return \Phar::running() ? sys_get_temp_dir().DIRECTORY_SEPARATOR.".illuminate-cli/$path" : storage_path("framework/$path");
    }
}

if (! function_exists('indent_lines')) {
    /**
     * Indent each line in the given content by the number of given spaces
     */
    function indent_lines(string $content, int $spaces): string
    {
        $result = [];
        $spacing = str_repeat(' ', $spaces);
        if ($content == '') {
            return '';
        }
        foreach (explode(PHP_EOL, $content) as $line) {
            $result[] = $spacing.$line;
        }

        return implode(PHP_EOL, $result);
    }
}
