<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

afterAll(function () {
    (new Filesystem)->deleteDirectory(test_workspace_path());
});

it('respects escaped directives', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    {{$name}}
    @@if(true)
        example
    @@endif

        @@if(true)
            example2
        @@endif
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--name' => 'Bob',
        '--compiled-path' => test_workspace_path('compiled'),
        '--no-cache' => true,
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    Bob
    @if(true)
        example
    @endif

        @if(true)
            example2
        @endif
    EOL);
});

it('escapes html', function () {
    $name = Str::random(10);
    $path = write_test_workspace_file($name, <<<'EOL'
    {{$html}}
    EOL);
    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--html' => '<script>alert("foo")</script>',
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    &lt;script&gt;alert(&quot;foo&quot;)&lt;/script&gt;
    EOL);
});
