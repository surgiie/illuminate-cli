<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function test_workspace_path(string $path = '')
{
    return rtrim(__DIR__.'/workspace'.'/'.$path);
}

function write_test_workspace_file(string $file, string $contents)
{
    $file = trim($file, '/');

    $path = test_workspace_path().$file;

    @mkdir(dirname($path), recursive: true);

    file_put_contents($path, $contents);

    return $path;
}
