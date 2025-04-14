<?php

namespace App\Commands\Validation;

use App\Commands\BaseCommand;
use App\Concerns\Helpers\CallsMethods;
use App\Concerns\Validation\UsesValidator;
use Illuminate\Support\Str;

class MakeCommand extends BaseCommand
{
    use CallsMethods, UsesValidator;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'validation:make
                                    {argument? : The input argument to validate.}
                                    {--rules= : The validation rules to apply.}
                                    {--json : Output the validation errors as json.}';

    /**
     * Determine if the command should have arbitrary options.
     */
    protected function shouldHaveArbitraryOptions(): bool
    {
        return true;
    }

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Make validator and validate arbitrary options.';

    /**
     * Parse arbitrary options and return input data and rules.
     */
    protected function parseOptionsForInputAndRules(array $options): array
    {
        foreach ($options as $key => $value) {
            if (str_ends_with($key, '-rules')) {
                $rules[substr($key, 0, -6)] = $value;

                continue;
            }

            $input[$key] = $this->processValueForTypeCast($value);

        }

        return [$input, $rules];
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->argument('argument')) {
            $input = ['argument' => $this->processValueForTypeCast($this->argument('argument'))];
            $rules = $this->option('rules') ? ['argument' => $this->option('rules')] : [];
        } else {
            [$input, $rules] = $this->parseOptionsForInputAndRules($this->arbitraryOptions->all());
        }

        if (empty($input)) {
            $this->fail('No input provided. Please specify input using the --<input-name> option(s).');
        }

        if (empty($rules)) {
            $this->fail('No rules provided. Please specify rules using the --<input-name>-rules option(s).');
        }

        $validator = $this->validator($input, $rules);
        try {
            $failed = $validator->fails();
        } catch (\BadMethodCallException $e) {
            $method = Str::after($e->getMessage(), 'Validator::');
            $method = Str::before($method, ' does not exist');
            $class = get_class($validator);
            $this->fail("Method $class::$method does not exist.");

            return 1;
        }
        if (! $failed) {
            $this->components->success('Validation passed!');

            return 0;
        }

        if ($this->option('json')) {
            $this->line(json_encode($validator->errors()->toArray(), JSON_PRETTY_PRINT));

            return 1;
        }

        foreach ($validator->errors()->all() as $error) {
            $this->components->error($error);
        }

        return 1;
    }
}
