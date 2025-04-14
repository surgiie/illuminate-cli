<?php

it('can call str method', function () {
    $this->artisan('support:str', [
        'string' => 'This is my name',
        '--before' => 'my',
    ])->expectsOutput('This is ')->assertExitCode(0);
});

it('can call multiple str methods', function () {
    $this->artisan('support:str', [
        'string' => 'This is my name',
        '--before' => 'name',
        '--after' => ' is',
    ])->expectsOutput(' my ')->assertExitCode(0);
});

it('handles bad method calls', function () {
    $this->artisan('support:str', [
        'string' => 'This is my name',
        '--badmethod' => true,
    ])->expectsOutputToContain("Method Illuminate\Support\Str::badmethod() does not exist.")->assertExitCode(1);
});

it('can control order value with with named arguments and @value', function () {
    $this->artisan('support:str', [
        'string' => 'foobar',
        '--is' => 'pattern:foo*,value:@value',
    ])->expectsOutput('true')->assertExitCode(0);
});

it('can json_encode with @json()', function () {
    $this->artisan('support:str', [
        'string' => 'This is my name',
        '--excerpt' => 'my,@json({"radius": 3})',
    ])->expectsOutput('...is my na...')->assertExitCode(0);
});
