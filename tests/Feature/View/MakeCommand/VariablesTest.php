<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

afterAll(function () {
    (new Filesystem)->deleteDirectory(test_workspace_path());
});

it('renders variables', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    {{$relationship}}
    {{$name}}
    something: {{ $something }}
    foo:
        bar: {{ $bar }}
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--relationship' => 'Uncle',
        '--bar' => 'baz',
        '--something' => 'foo',
        '--name' => 'Bob',
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    Uncle
    Bob
    something: foo
    foo:
        bar: baz
    EOL);
});

it('renders indented variables', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    {{$relationship}}
        {{$name}}
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--relationship' => 'Uncle',
        '--name' => 'Bob',
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    Uncle
        Bob
    EOL);
});
it('can use overwrite vars with --require file', function () {
    $name = Str::random(10);

    $templatePath = write_test_workspace_file($name, <<<'EOL'
    Hello {{ $name }}
    EOL);

    $requireFile = write_test_workspace_file($name.'-require.php', <<<'EOL'
    <?php

    return [
        "name" => "Not Bob"
    ];
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $templatePath,
        '--name' => 'Bob',
        '--require' => [$requireFile],
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    Hello Not Bob
    EOL);
});

it('can load variable data from json files', function () {
    $name = Str::random(10);

    $path = write_test_workspace_file("$name.yaml", <<<'EOL'
    name: {{ $name }}
    EOL);

    write_test_workspace_file('vars.json', <<<'EOL'
    {
        "name": "Doug"
    }
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--from-json' => [test_workspace_path('vars.json')],
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Doug
    EOL);
});

it('can load variable data from yaml files', function () {
    $name = Str::random(10);

    $path = write_test_workspace_file("$name.txt", <<<'EOL'
    name: {{ $name }}
    last_name: {{ $lastName }}
    EOL);

    write_test_workspace_file('vars.yaml', <<<'EOL'
    "name": "Doug"
    "last_name": "Thompson"
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--from-yaml' => [test_workspace_path('vars.yaml')],
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Doug
    last_name: Thompson
    EOL);
});

it('can load variable data from env files', function () {
    $name = Str::random(10);

    $path = write_test_workspace_file("$name.yaml", <<<'EOL'
    name: {{ $name }}
    EOL);

    write_test_workspace_file('.env.vars', <<<'EOL'
    NAME=Doug
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--from-env' => [test_workspace_path('.env.vars')],
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Doug
    EOL);
});
