<?php

it('can call stringable method', function () {
    $this->artisan('support:stringable', [
        'string' => 'foo',
        '--append' => 'bar',
    ])->expectsOutput('foobar')->assertExitCode(0);
});

it('can call stringable with multiple methods', function () {
    $this->artisan('support:stringable', [
        'string' => 'foo',
        '--append' => 'bar',
        '--prepend' => 'foo',
    ])->expectsOutput('foofoobar')->assertExitCode(0);
});

it('manipulates each item in collection when string becomes collection', function () {
    $cmd = $this->artisan('support:stringable', [
        'string' => 'FooBarBaz',
        '--ucsplit' => null,
        '--upper' => null,
    ]);
    $jsonOutput = json_encode([
        'FOO',
        'BAR',
        'BAZ',
    ], JSON_PRETTY_PRINT);

    $cmd->expectsOutputToContain($jsonOutput);

    $cmd->assertExitCode(0);
});

it('respects escaped commas in method arguments', function () {
    $this->artisan('support:stringable', [
        'string' => 'foo',
        '--append' => 'bar\\,baz',
    ])->expectsOutput('foobar,baz')->assertExitCode(0);
});
it('respects escaped commas in method arguments on collections', function () {
    $this->artisan('support:stringable', [
        'string' => 'foo,bar,baz',
        '--split' => '/[\s\,]+/',
        '--upper' => null,
    ])->expectsOutputToContain(json_encode([
        'FOO',
        'BAR',
        'BAZ',
    ], JSON_PRETTY_PRINT))->assertExitCode(0);
});
