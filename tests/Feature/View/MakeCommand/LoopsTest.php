<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

afterAll(function () {
    (new Filesystem)->deleteDirectory(test_workspace_path());
});

it('can render @foreach', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    pets:
        @foreach($dogs as $dog)
        - {{ $dog }}
        @endforeach
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
        '--dogs' => ['Rex', 'Charlie'],
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    pets:
        - Rex
        - Charlie
    EOL);
});

it('can render indented @foreach', function () {

    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    pets:
            @foreach($dogs as $dog)
            - {{ $dog }}
            @endforeach
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
        '--dogs' => ['Rex', 'Charlie'],
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    pets:
            - Rex
            - Charlie
    EOL);
});

it('can render @forelse', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    pets:
        @forelse($dogs as $dog)
        - {{ $dog }}
        @empty
        - 'I have no dogs'
        @endforelse
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
        '--dogs' => ['Rex', 'Charlie'],
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    pets:
        - Rex
        - Charlie
    EOL);
});

it('can render indented @forelse', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    pets:
            @forelse($dogs as $dog)
            - {{ $dog }}
            @empty
            - 'I have no dogs'
            @endforelse
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
        '--dogs' => ['Rex', 'Charlie'],
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    pets:
            - Rex
            - Charlie
    EOL);
});

it('can render @for', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    favorite_numbers:
    @for ($i = 0; $i < 3; $i++)
        - '{{ $i }}'
    @endfor
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    favorite_numbers:
        - '0'
        - '1'
        - '2'
    EOL);
});

it('can render indented @for', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    favorite_numbers:
        @for ($i = 0; $i < 3; $i++)
            - '{{ $i }}'
        @endfor
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
    ])->assertExitCode(0);
    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    favorite_numbers:
            - '0'
            - '1'
            - '2'
    EOL);
});

it('can render @while', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    favorite_numbers:
    @php($count = 0)
    @while ($count < 3)
        - '{{ $count }}'
        @php($count ++)
    @endwhile
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
    ])->assertExitCode(0);
    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    favorite_numbers:
        - '0'
        - '1'
        - '2'
    EOL);
});

it('can render indented @while', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    favorite_numbers:
    @php($count = 0)
        @while ($count < 3)
            - '{{ $count }}'
            @php($count ++)
        @endwhile
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    favorite_numbers:
            - '0'
            - '1'
            - '2'
    EOL);
});
