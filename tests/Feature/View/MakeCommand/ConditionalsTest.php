<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

afterAll(function () {
    (new Filesystem)->deleteDirectory(test_workspace_path());
});
/**
 * ------------------------------------------------------------------------------------------
 * CompilesConditionals tests
 * ------------------------------------------------------------------------------------------
 */
it('can render @if', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    contact_info:
        phone: 1234567890
    @if($includeAddress)
    street_info: 123 Lane.
    @endif
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--compiled-path' => test_workspace_path('compiled'),
        '--no-cache' => true,
        '--favorite-food' => 'Pizza',
        '--include-address' => true,
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    contact_info:
        phone: 1234567890
    street_info: 123 Lane.
    EOL);
});

it('can render indented @if', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    contact_info:
        phone: 1234567890
        @if($includeAddress)
        street_info: 123 Lane.
        @endif
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--compiled-path' => test_workspace_path('compiled'),
        '--no-cache' => true,
        '--name' => 'Jeff',
        '--favorite-food' => 'Salad',
        '--include-address' => true,
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Jeff
    favorite_food: Salad
    contact_info:
        phone: 1234567890
        street_info: 123 Lane.
    EOL);
});

it('can render @else', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    phone: 1234567890
    @if($includeAddress ?? false)
    street_info: 123 Lane.
    @else
    street_info: none
    @endif
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--compiled-path' => test_workspace_path('compiled'),
        '--no-cache' => true,
        '--name' => 'Bob',
        '--favorite-food' => 'Cereal',
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Cereal
    phone: 1234567890
    street_info: none
    EOL);
});

it('can render indented @else', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    phone: 1234567890
    contact_info:
        @if($includeAddress ?? false)
        street_info: 123 Lane.
        @else
        street_info: none
        @endif
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--compiled-path' => test_workspace_path('compiled'),
        '--no-cache' => true,
        '--name' => 'Julia',
        '--favorite-food' => 'Oatmeal',
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Julia
    favorite_food: Oatmeal
    phone: 1234567890
    contact_info:
        street_info: none
    EOL);
});

it('can render @switch', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
    family_info:
    @switch($oldest)
    @case(1)
        oldest_child: true
        @break
    @case(2)
        oldest_child: false
        @break
    @endswitch
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--cache-path' => test_workspace_path('cache'),
        '--name' => 'Bob',
        '--no-cache' => true,
        '--favorite-food' => 'Pizza',
        '--oldest' => true,
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    family_info:
        oldest_child: true
    EOL);
});

it('can render indented @switch', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
        name: {{ $name }}
        favorite_food: {{ $favoriteFood }}
        family_info:
            @switch($oldest)
            @case(1)
                oldest_child: true
                @break
            @case(2)
                oldest_child: false
                @break
            @endswitch
        EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--cache-path' => test_workspace_path('cache'),
        '--name' => 'Bob',
        '--favorite-food' => 'Pizza',
        '--oldest' => true,
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
        name: Bob
        favorite_food: Pizza
        family_info:
                oldest_child: true
        EOL);
});
