<?php

use Illuminate\Support\Str;

it('can render @component', function () {
    $componentFileName = Str::random(10);
    write_test_workspace_file($componentFileName, <<<'EOL'
    data: {{ $data }}
    EOL);

    $mainFileName = Str::random(10);
    $path = write_test_workspace_file($mainFileName, <<<"EOL"
    name: {{ \$name }}
    favorite_food: {{ \$favoriteFood }}
    @component('$componentFileName', ['data'=>'foobar'])
    @endcomponent
    favorite_numbers:
    @php(\$count = 0)
    @while (\$count < 3)
        - '{{ \$count }}'
        @php(\$count ++)
    @endwhile
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--no-cache' => true,
        '--favorite-food' => 'Pizza',
        '--compiled-path' => test_workspace_path('compiled'),
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    data: foobar
    favorite_numbers:
        - '0'
        - '1'
        - '2'
    EOL);
});

it('can render indented @component', function () {
    $componentFileName = Str::random(10);
    write_test_workspace_file($componentFileName, <<<'EOL'
    data: {{ $data }}
    indented: true
    EOL);

    $mainFileName = Str::random(10);
    $path = write_test_workspace_file($mainFileName, <<<"EOL"
    name: {{ \$name }}
        favorite_food: {{ \$favoriteFood }}
        @component('$componentFileName', ['data'=>'foobar'])
        @endcomponent
    favorite_numbers:
    @php(\$count = 0)
    @while (\$count < 3)
        - '{{ \$count }}'
        @php(\$count ++)
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
        data: foobar
        indented: true
    favorite_numbers:
        - '0'
        - '1'
        - '2'
    EOL);
});

it('can render component @slot', function () {
    $mainFileName = Str::random(10);
    $componentFileName = Str::random(10);

    write_test_workspace_file($componentFileName, <<<'EOL'
    data: {{ $data }}
    {{ $format ?? 'format: yaml' }}
    EOL);

    $path = write_test_workspace_file($mainFileName, <<<"EOL"
    name: {{ \$name }}
    favorite_food: {{ \$favoriteFood }}
    @component('$componentFileName', ['data'=>'foobar'])
    @slot('format')
    format: json
    @endslot
    @endcomponent
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    data: foobar
    format: json
    EOL);
});

it('does not indent rendered indented @slot.', function () {
    $mainFileName = Str::random(10);
    $componentFileName = Str::random(10);

    write_test_workspace_file($componentFileName, <<<'EOL'
    data: {{ $data }}
    other:
        {{ $format ?? 'format: yaml' }}
    EOL);

    $path = write_test_workspace_file($mainFileName, <<<"EOL"
    name: {{ \$name }}
    favorite_food: {{ \$favoriteFood }}
    @component('$componentFileName', ['data'=>'foobar'])
        @slot('format')
            format: json
        @endslot
    @endcomponent
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--compiled-path' => test_workspace_path('compiled'),
        '--favorite-food' => 'Pizza',
    ]);
    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    data: foobar
    other:
        format: json
    EOL);
});

it('can render blade x anonymous components with require file.', function () {
    $mainFileName = Str::random(10);
    $componentName = Str::random(10);
    write_test_workspace_file($componentName, <<<'EOL'
    name: {{ $name }}
    EOL);

    @mkdir(test_workspace_path('components'));

    $path = write_test_workspace_file($mainFileName, <<<"EOL"
    <x-$componentName :name='\$name' />
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
    EOL);

    $testWorkspace = test_workspace_path();
    $requireFile = <<<"EOL"
    <?php
    use Illuminate\\Support\\Facades\\Blade;
    Blade::anonymousComponentPath(realpath('$testWorkspace'));
    EOL;

    write_test_workspace_file($requireName = Str::random(10).'.php', $requireFile);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--require' => [test_workspace_path($requireName)],
        '--favorite-food' => 'Pizza',
        '--oldest' => true,
        '--compiled-path' => test_workspace_path('compiled'),
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    name: Bob
    favorite_food: Pizza
    family_info:
        oldest_child: true
    EOL);
});

it('can render indented blade x anonymous components', function () {
    $mainFileName = Str::random(10);
    $componentName = Str::random(10);
    write_test_workspace_file($componentName, <<<'EOL'
    name: {{ $name }}
    EOL);

    @mkdir(test_workspace_path('components'));

    $path = write_test_workspace_file($mainFileName, <<<"EOL"
        <x-$componentName :name='\$name' />
        <x-$componentName name='Not Bob' />
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
    EOL);

    $testWorkspace = test_workspace_path();
    $requireFile = <<<"EOL"
    <?php
    use Illuminate\\Support\\Facades\\Blade;
    Blade::anonymousComponentPath(realpath('$testWorkspace'));
    EOL;

    write_test_workspace_file($requireName = Str::random(10).'.php', $requireFile);
    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--require' => [test_workspace_path($requireName)],
        '--name' => 'Bob',
        '--favorite-food' => 'Pizza',
        '--oldest' => true,
        '--compiled-path' => test_workspace_path('compiled'),
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
        name: Bob
        name: Not Bob
    favorite_food: Pizza
    family_info:
        oldest_child: true
    EOL);
});

it('can render blade x class components via --require', function () {
    $templateName = Str::random(10);
    $requireFileName = Str::random(10);
    $componentName = Str::random(10);
    $componentTemplateName = Str::random(10);
    $view = write_test_workspace_file($componentTemplateName, <<<'EOL'
    {{ $type }}: {{ $message }}
    EOL);

    $class = <<<"EOL"
<?php
namespace App\Views\Components;

class Alert extends \Illuminate\View\Component
{
    public \$type;
    public \$message;
    public function __construct(\$type, \$message)
    {
        \$this->type = \$type;
        \$this->message = \$message;
    }
    public function render()
    {
        return view("$view", [
            'type' => \$this->type,
            'message' => \$this->message,
        ]);
    }
}
EOL;

    write_test_workspace_file($componentName, $class);

    $path = write_test_workspace_file($templateName, <<<"EOL"
    <x-$componentName :type='\$type' :message='\$message' />
    EOL);

    $requireFile = <<<"EOL"
    <?php
    require_once __DIR__ . '/$componentName';
    use Illuminate\Support\Facades\Blade;
    use App\Views\Components\Alert;

    Blade::component('$componentName', Alert::class);
    EOL;

    $requireFile = write_test_workspace_file($requireFileName, $requireFile);
    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--compiled-path' => test_workspace_path('compiled'),
        '--require' => [$requireFile],
        '--message' => 'Something went wrong!',
        '--type' => 'error',
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    error: Something went wrong!
    EOL);
});
