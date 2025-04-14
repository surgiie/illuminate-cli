<?php

namespace App\Commands\Support;

use App\Commands\BaseCommand;
use App\Concerns\Helpers\CallsMethods;
use App\Concerns\Validation\UsesValidator;
use Illuminate\Support\Number;

class MyClass
{
    public static function greet($name, $greeting = 'Hello')
    {
        echo "$greeting, $name!";
    }
}
class NumberCommand extends BaseCommand
{
    use CallsMethods, UsesValidator;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'support:number
                                    {number : The number to process.}
                                    {--debug : Output debug information.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Format a number.';

    /** Determine if the command should have arbitrary options. */
    protected function shouldHaveArbitraryOptions(): bool
    {
        return true;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {

        $number = $this->processValueForTypeCast($this->argument('number'));

        $validator = $this->validator([
            'number' => $number,
        ], [
            'number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $this->fail($validator->errors()->first());
        }

        foreach ($this->arbitraryOptionsOrdered as $option) {
            $method = $option[0];
            $args = $option[1];
            $args = rtrim($number.','.$args, ',');
            $args = str_replace('@value', $number, $args);
            $number = $this->callMethodFromCommandArgs(Number::class, $method, $args);
        }

        if (! is_string($number)) {
            $number = json_encode($number, JSON_PRETTY_PRINT);
        }

        $this->line($number);

        return 0;
    }
}
