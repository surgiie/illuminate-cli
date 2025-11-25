<?php

test('install chrome driver command exists', function () {
    $this->artisan('dusk:chrome-driver', ['--help' => true])
        ->assertExitCode(0);
});

test('command is registered', function () {
    // This test verifies the command is registered without actually installing
    // since that would require system-level changes
    $this->artisan('list')
        ->assertExitCode(0);

    expect(
        collect(app('Illuminate\Contracts\Console\Kernel')->all())
            ->keys()
            ->contains('dusk:chrome-driver')
    )->toBeTrue();
});
