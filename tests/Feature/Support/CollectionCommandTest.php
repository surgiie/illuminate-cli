<?php

it('can call collection method', function () {
    $cmd = $this->artisan('support:collection', [
        'collection' => '["foo", "bar"]',
        '--push' => 'baz',
    ]);

    $json = json_encode(['foo', 'bar', 'baz'], JSON_PRETTY_PRINT);
    $cmd->expectsOutput($json);

    $cmd->assertExitCode(0);
});

it('can call multiple collection methods', function () {
    $cmd = $this->artisan('support:collection', [
        'collection' => '["foo", "bar"]',
        '--push' => 'baz',
        '--pop' => null,
    ]);

    $cmd->expectsOutput('"baz"');

    $cmd->assertExitCode(0);
});

it('handles bad method calls', function () {
    $this->artisan('support:collection', [
        'collection' => '["foo", "bar"]',
        '--badmethod' => null,
    ])->expectsOutputToContain("Method Illuminate\Support\Collection::badmethod() does not exist.")->assertExitCode(1);
});

it('handles method calls that accept callback arguments', function () {
    $cmd = $this->artisan('support:collection', [
        'collection' => '["foo", "bar"]',
        '--map' => 'whatever',
    ]);
    $cmd->expectsOutputToContain('Methods that accept callbacks are not supported')->assertExitCode(1);
});

it('can handle @json() casting', function () {
    $this->artisan('support:collection', [
        'collection' => '["foo", "bar"]',
        '--replace' => '@json({"1": "baz"})',
    ])->expectsOutput(json_encode([
        'foo',
        'baz',
    ], JSON_PRETTY_PRINT))->assertExitCode(0);
});
