<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

afterAll(function () {
    (new Filesystem)->deleteDirectory(test_workspace_path());
});

it('compiles basic layout', function () {
    $name = Str::random(10);
    $layoutName = Str::random(10);
    $path = write_test_workspace_file($name, <<<"EOL"
    @extends("$layoutName")
    @section("content")
    name: {{ \$name }}
    favorite_food: {{ \$favoriteFood }}
    family_info:
        @switch(\$oldest)
        @case(1)
        oldest_child: true
            @break
        @case(2)
        oldest_child: false
            @break
        @endswitch
    @endsection
    EOL);

    write_test_workspace_file($layoutName, <<<'EOL'
    @yield("content")
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
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

it('compiles indented @yield', function () {
    $name = Str::random(10);
    $layoutName = Str::random(10);
    $path = write_test_workspace_file($name, <<<"EOL"
    @extends("$layoutName")
    @section("content")
    family_info:
        @switch(\$oldest)
        @case(1)
        oldest_child: true
            @break
        @case(2)
        oldest_child: false
            @break
        @endswitch
    @endsection
    EOL);

    write_test_workspace_file($layoutName, <<<'EOL'
    name: {{ $name }}
    favorite_food: {{ $favoriteFood }}
        @yield("content")
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
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

it('compiles indented @section', function () {
    $name = Str::random(10);
    $layoutName = Str::random(10);
    $path = write_test_workspace_file($name, <<<"EOL"
    @extends("$layoutName")
    @section("content")
        name: {{ \$name }}
        favorite_food: {{ \$favoriteFood }}
        family_info:
            @switch(\$oldest)
            @case(1)
            oldest_child: true
                @break
            @case(2)
            oldest_child: false
                @break
            @endswitch
    @endsection
    EOL);

    write_test_workspace_file($layoutName, <<<'EOL'
    @yield("content")
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
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

it('compiles @push', function () {
    $name = Str::random(10);
    $layoutName = Str::random(10);
    $path = write_test_workspace_file($name, <<<"EOL"
    @extends("$layoutName")
    @section("content")
    name: {{ \$name }}
    favorite_food: {{ \$favoriteFood }}
    family_info:
        @switch(\$oldest)
        @case(1)
        oldest_child: true
            @break
        @case(2)
        oldest_child: false
            @break
        @endswitch
    @push('head')
    title: About Me
    @endpush
    @endsection
    EOL);

    write_test_workspace_file($layoutName, <<<'EOL'
    head:
        @stack('head')
    @yield("content")
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--name' => 'Bob',
        '--favorite-food' => 'Pizza',
        '--oldest' => true,
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    head:
        title: About Me
    name: Bob
    favorite_food: Pizza
    family_info:
        oldest_child: true
    EOL);
});

it('compiles indented @push', function () {
    $name = Str::random(10);
    $layoutName = Str::random(10);
    $path = write_test_workspace_file($name, <<<"EOL"
    @extends("$layoutName")
    @section("content")
    name: {{ \$name }}
    favorite_food: {{ \$favoriteFood }}
        @push('head')
        title: About Me
        @endpush
    family_info:
        @switch(\$oldest)
        @case(1)
        oldest_child: true
            @break
        @case(2)
        oldest_child: false
            @break
        @endswitch
    @endsection
    EOL);

    write_test_workspace_file($layoutName, <<<'EOL'
    head:
    @stack('head')
    @yield("content")
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--name' => 'Bob',
        '--favorite-food' => 'Pizza',
        '--oldest' => true,
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    head:
        title: About Me
    name: Bob
    favorite_food: Pizza
    family_info:
        oldest_child: true
    EOL);
});

it('compiles @pushIf', function () {
    $name = Str::random(10);
    $layoutName = Str::random(10);
    $path = write_test_workspace_file($name, <<<"EOL"
    @extends("$layoutName")
    @section("content")
    name: {{ \$name }}
    favorite_food: {{ \$favoriteFood }}
    family_info:
        @switch(\$oldest)
        @case(1)
        oldest_child: true
            @break
        @case(2)
        oldest_child: false
            @break
        @endswitch
    @pushIf(true, 'head')
    title: About Me
    @endPushIf
    @endsection
    EOL);

    write_test_workspace_file($layoutName, <<<'EOL'
    head:
        @stack('head')
    @yield("content")
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--name' => 'Bob',
        '--favorite-food' => 'Pizza',
        '--oldest' => true,
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    head:
        title: About Me
    name: Bob
    favorite_food: Pizza
    family_info:
        oldest_child: true
    EOL);
});

it('compiles indented @pushIf', function () {
    $name = Str::random(10);
    $layoutName = Str::random(10);
    $path = write_test_workspace_file($name, <<<"EOL"
    @extends("$layoutName")
    @section("content")
    name: {{ \$name }}
    favorite_food: {{ \$favoriteFood }}
    family_info:
        @switch(\$oldest)
        @case(1)
        oldest_child: true
            @break
        @case(2)
        oldest_child: false
            @break
        @endswitch
        @pushIf(true, 'head')
        title: About Me
        @endPushIf
    @endsection
    EOL);

    write_test_workspace_file($layoutName, <<<'EOL'
    head:
    @stack('head')
    @yield("content")
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--name' => 'Bob',
        '--favorite-food' => 'Pizza',
        '--oldest' => true,
    ])->assertExitCode(0);
    $cmd->expectsOutput(<<<'EOL'
    head:
        title: About Me
    name: Bob
    favorite_food: Pizza
    family_info:
        oldest_child: true
    EOL);
});
