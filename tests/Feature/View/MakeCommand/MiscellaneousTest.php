<?php

// General tests that dont fit into a specific category or focus area.

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

afterAll(function () {
    (new Filesystem)->deleteDirectory(test_workspace_path());
});

it('throws error when file doesnt exist', function () {
    $this->artisan('view:make', ['path' => '/i-dont-exist'])
        ->expectsOutputToContain('The path input file does not exist.')
        ->assertExitCode(1);
});

it('renders a complex file as expected', function () {
    $name = Str::random(10);
    $layout = Str::random(10);
    $path = write_test_workspace_file($name, <<<"EOL"
    @extends("$layout")
    @section("content")
        @if(\$nodeAffinity)
        affinity:
            nodeAffinity:
            requiredDuringSchedulingIgnoredDuringExecution:
                nodeSelectorTerms:
            —matchExpressions:
                —key: disktype
                    operator: In
                    values:
                —ssd
        @endif
        containers:
        —name: nginx
            image: nginx
            ports:
            —containerPort: 80
    @endsection
    EOL);

    write_test_workspace_file('strategy', <<<'EOL'
    strategy:
        type: RollingUpdate
    EOL);

    write_test_workspace_file($layout, <<<'EOL'
    apiVersion: {{ $apiVersion }}
    kind: Deployment
    metadata:
    name: {{ $name }}
    labels:
        @foreach($labels as $label)
        {{ $label }}
        @endforeach
    spec:
    selector:
        matchLabels:
        @foreach($labels as $label)
        {{ $label }}
        @endforeach
    replicas: {{ $replicas }}
    @include('strategy')
    template:
        metadata:
        labels:
            @foreach($labels as $label)
            {{ $label }}
            @endforeach
        spec:
        @yield('content')
    EOL);

    $cmd = $this->artisan('view:make', [
        'path' => $path,
        '--no-cache' => true,
        '--compiled-path' => test_workspace_path('compiled'),
        '--api-version' => 'apps/v1',
        '--name' => 'nginx-deployment',
        '--replicas' => 3,
        '--node-affinity' => true,
        '--labels' => [
            'app: web',
            'backend: api',
        ],
    ])->assertExitCode(0);

    $cmd->expectsOutput(<<<'EOL'
    apiVersion: apps/v1
    kind: Deployment
    metadata:
    name: nginx-deployment
    labels:
        app: web
        backend: api
    spec:
    selector:
        matchLabels:
        app: web
        backend: api
    replicas: 3
    strategy:
        type: RollingUpdate
    template:
        metadata:
        labels:
            app: web
            backend: api
        spec:
            affinity:
                nodeAffinity:
                requiredDuringSchedulingIgnoredDuringExecution:
                    nodeSelectorTerms:
                —matchExpressions:
                    —key: disktype
                        operator: In
                        values:
                    —ssd
            containers:
            —name: nginx
                image: nginx
                ports:
                —containerPort: 80
    EOL);
});
