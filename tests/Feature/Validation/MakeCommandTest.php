<?php

it('validates single argument', function () {
    $cmd = $this->artisan('validation:make', [
        'argument' => '@int(20)',
        '--rules' => 'required|int|min:21',
    ]);
    $cmd->assertExitCode(1);
    $cmd->expectsOutputToContain('The argument input must be at least 21.');
});
it('validates with multiple options', function () {
    $cmd = $this->artisan('validation:make', [
        '--name' => 'Bob',
        '--age' => '20',
        '--food' => 'Pizza',
        '--cast-age' => 'int',
        '--name-rules' => 'required|string|max:255',
        '--age-rules' => 'required|int|min:21',
        '--food-rules' => 'required|string|in:Burger,Sushi',
    ]);
    $cmd->assertExitCode(1);
    $cmd->expectsOutputToContain('The age input must be at least 21.');
    $cmd->expectsOutputToContain('The selected food input is invalid.');
});

it('handles bad rule method calls', function () {
    $cmd = $this->artisan('validation:make', [
        '--name' => 'Bob',
        '--name-rules' => 'required|badrule',
    ]);
    $cmd->assertExitCode(1);
    $cmd->expectsOutputToContain("Method Illuminate\Validation\Validator::validateBadrule does not exist");
});
it('returns errors as json', function () {
    $cmd = $this->artisan('validation:make', [
        '--age' => '@int(20)',
        '--food' => 'Pizza',
        '--age-rules' => 'required|int|min:21',
        '--food-rules' => 'required|string|in:Burger,Sushi',
        '--json' => true,
    ]);
    $cmd->assertExitCode(1);
    $json = json_encode([
        'age' => ['The age input must be at least 21.'],
        'food' => ['The selected food input is invalid.'],
    ], JSON_PRETTY_PRINT);

    $cmd->expectsOutputToContain($json);

});
