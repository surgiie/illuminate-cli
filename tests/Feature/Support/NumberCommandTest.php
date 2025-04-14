<?php

it('can call number method', function () {
    $this->artisan('support:number', [
        'number' => '10',
        '--spell' => null,
    ])->expectsOutput('ten')->assertExitCode(0);
});

it('can call multiple number methods', function () {
    $this->artisan('support:number', [
        'number' => '105',
        '--clamp' => '10,100',
        '--spell' => null,
    ])->expectsOutput('one hundred')->assertExitCode(0);
});

it('handles bad input', function () {
    $this->artisan('support:number', [
        'number' => 'nan',
    ])->expectsOutputToContain('The number input must be a number.')->assertExitCode(1);
});
it('handles bad method calls', function () {
    $this->artisan('support:number', [
        'number' => '10',
        '--badmethod' => true,
    ])->expectsOutputToContain("Method Illuminate\Support\Number::badmethod() does not exist.")->assertExitCode(1);
});
