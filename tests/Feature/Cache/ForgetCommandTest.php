<?php

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

it('forgets item in cache', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('has')
        ->once()
        ->with('test')
        ->andReturn(true);

    $cacheMock->shouldReceive('forget')
        ->once()
        ->with('test')
        ->andReturn(true);

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:forget', [
        'key' => 'test',
    ]);

    $cmd->assertExitCode(0);
    $cmd->expectsOutputToContain("The item with key 'test' has been forgotten.");
});
it('errors when cannot forget item in cache', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('has')
        ->once()
        ->with('test')
        ->andReturn(true);

    $cacheMock->shouldReceive('forget')
        ->once()
        ->with('test')
        ->andReturn(false);

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:forget', [
        'key' => 'test',
    ]);

    $cmd->assertExitCode(1);
    $cmd->expectsOutputToContain("The item with key 'test' could not be forgotten.");
});
it('errors when item is not in cache', function () {
    $cacheMock = Mockery::mock(Repository::class);
    $cacheMock->shouldReceive('has')
        ->once()
        ->with('test')
        ->andReturn(false);

    Cache::shouldReceive('store')
        ->andReturn($cacheMock);

    $cmd = $this->artisan('cache:forget', [
        'key' => 'test',
    ]);

    $cmd->assertExitCode(1);
    $cmd->expectsOutputToContain("The item with key 'test' does not exist in the cache.");
});
