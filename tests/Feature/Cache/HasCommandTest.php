<?php

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

it('checks cache has item', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('has')
        ->once()
        ->with('test')
        ->andReturn(true);

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:has', [
        'key' => 'test',
    ]);
    $cmd->assertExitCode(0);
    $cmd->expectsOutputToContain('Cache has item with key \'test\'');
});

it('checks cache does not have item', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('has')
        ->once()
        ->with('test')
        ->andReturn(false);

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:has', [
        'key' => 'test',
    ]);
    $cmd->assertExitCode(1);
    $cmd->expectsOutputToContain('Cache does not have item with key \'test\'');
});

it('returns json output for has command', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('has')
        ->once()
        ->with('test')
        ->andReturn(false);

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:has', [
        'key' => 'test',
        '--json' => true,
    ]);
    $cmd->assertExitCode(1);
    $json = json_encode(['key' => 'test', 'has' => false], JSON_PRETTY_PRINT);
    $cmd->expectsOutputToContain($json);
});
