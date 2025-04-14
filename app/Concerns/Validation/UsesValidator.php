<?php

namespace App\Concerns\Validation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;

trait UsesValidator
{
    /**
     * Return a new Validator instance.
     */
    protected function validator(array $data, array $rules, array $messages = [], array $attributes = []): Validator
    {
        $loader = new FileLoader(new Filesystem, base_path('resources/lang'));

        $translator = new Translator($loader, 'en');

        $factory = new ValidatorFactory($translator, $this->laravel);

        return $factory->make(
            $data,
            $rules,
            $messages,
            $attributes,
        );
    }
}
