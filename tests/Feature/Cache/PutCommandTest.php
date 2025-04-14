<?php

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

it('puts items in cache', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('put')
        ->once()
        ->with('test', 'value', null);

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:put', [
        'data' => 'value',
        '--key' => 'test',
    ]);
    $cmd->assertExitCode(0);
    $cmd->expectsOutputToContain("Item put in cache under key 'test'");
});

it('puts items in cache with ttl expiration', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('put')
        ->once()
        ->with('test', 'value', 60);

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:put', [
        'data' => 'value',
        '--key' => 'test',
        '--seconds' => 60,
    ]);
    $cmd->assertExitCode(0);
    $cmd->expectsOutputToContain("Item put in cache under key 'test' with a TTL of 60 seconds");
});

it('seconds options must be number', function () {
    $cmd = $this->artisan('cache:put', [
        'data' => 'value',
        '--key' => 'test',
        '--seconds' => 'notnum',
    ]);
    $cmd->assertExitCode(1);
    $cmd->expectsOutputToContain('The seconds input must be an integer.');
});
