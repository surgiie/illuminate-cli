<?php

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

it('errors when trying to get non existing item', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('has')
        ->once()
        ->with('test')
        ->andReturn(false);

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:get', [
        'key' => 'test',
    ]);
    $cmd->assertExitCode(1);
    $cmd->expectsOutputToContain("The item with key 'test' does not exist in the cache.");
});

it('gets item from cache when it exists', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('has')
        ->once()
        ->with('test')
        ->andReturn(true);
    $cacheMock->shouldReceive('get')
        ->once()
        ->with('test')
        ->andReturn('value');

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:get', [
        'key' => 'test',
    ]);
    $cmd->assertExitCode(0);
    $cmd->expectsOutputToContain('value');
});
