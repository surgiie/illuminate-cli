<?php

namespace App\Concerns\Helpers;

use Illuminate\Support\Str;
use ReflectionException;
use ReflectionMethod;
use Throwable;

trait CallsMethods
{
    /**
     * The exception thrown when a method call fails.
     *
     * @var \Throwable|null
     */
    protected $failedCalledMethodException = null;

    /**
     * Get the regular expression for matching type cast syntax.
     */
    protected function getCastRegex(): string
    {
        return '/^@(json|int|float|bool|null)\((.*)\)$/';
    }

    /**
     * Determine if the value should be cast to a type.
     *
     * @param  string  $value  The value to check
     */
    protected function shouldCastValue(string $value): bool
    {
        return preg_match($this->getCastRegex(), $value);
    }

    /**
     * Cast or serialize value to a type if wrapped by special @<type>(<value>) syntax.
     *
     * Supported types: json, int, float, bool, null
     *
     * @param  string  $value  The value to process
     * @return mixed The processed value
     */
    public function processValueForTypeCast(string $value)
    {
        preg_match($this->getCastRegex(), $value, $matches);

        if (! $matches) {

            return $value;
        }
        $this->debug("Casting/Encoding value '{$matches[2]}' to: {$matches[1]}");
        switch ($matches[1]) {
            case 'json':
                if (! Str::isJson($matches[2])) {
                    $this->fail("Invalid JSON string: {$matches[2]}");
                }

                return json_decode($matches[2], true);
            case 'int':
                return (int) $matches[2];
            case 'float':
                return (float) $matches[2];
            case 'null':
                return null;
            case 'bool':
                return filter_var($matches[2], FILTER_VALIDATE_BOOLEAN);
            default:
                return $value;
        }
    }

    /**
     * Name arguments if arguments are named or use reflection to get the method argument names.
     *
     * @param  object|string  $class  The class or object instance
     * @param  string  $method  The method name
     * @param  array<int, string>  $args  The arguments to name and cast
     * @return array<string, mixed> Named and cast arguments
     */
    protected function nameAndCastArguments(object|string $class, string $method, array $args): mixed
    {
        $namedArgs = [];
        $refMethod = new \ReflectionMethod($class, $method);
        $functionParams = $refMethod->getParameters();
        foreach ($args as $index => $value) {

            if (str_contains($value, '@json')) {
                $matches = false;
            } else {
                preg_match('/^(.+):(.+)$/', $value, $matches);
            }

            if (! $matches) {
                $name = $functionParams[$index]->getName();
                $namedArgs[$name] = $this->processValueForTypeCast($value);

                continue;
            }

            $name = $matches[1];
            $value = $matches[2];
            $namedArgs[$name] = $this->processValueForTypeCast($value);
        }

        return $namedArgs;

    }

    /**
     * Call method on given object or class.
     *
     * @param  string|object  $obj  The object instance or class name
     * @param  string  $method  The method name to call
     * @param  string|null  $args  String representation of arguments
     * @return mixed The result of the method call
     */
    protected function callMethodFromCommandArgs(string|object $obj, string $method, ?string $args = ''): mixed
    {
        if (is_string($obj) && ! class_exists($obj)) {
            $this->fail("Class {$obj} does not exist.");
        }

        try {
            $args = $this->parseStringArguments($args, $obj, $method);
            $method = Str::camel($method);
            $refMethod = new ReflectionMethod($obj, $method);
            $label = is_string($obj) ? $obj : get_class($obj).' instance';
            $this->debug("Calling method '{$method}' on {$label} with arguments: ".json_encode($args));

            $obj = $refMethod->invokeArgs(is_string($obj) ? null : $obj, $args);

            $this->debug("Result of static method '{$method}': ".json_encode($obj));

        } catch (Throwable|ReflectionException $e) {
            $error = $e->getMessage();

            $this->failedCalledMethodException = $e;

            if (str_contains($error, 'must be of type callable')) {
                $error = 'Methods that accept callbacks are not supported by cli.';
            }

            $this->fail($error);
        }

        return $obj;
    }

    /**
     * Split arguments string list by comma, respecting escaped commas and parentheses.
     *
     * @param  string  $input  The input string to split
     * @return array<int, string> Array of split arguments
     */
    protected function splitArguments(string $input)
    {
        // split by comma not preceded by a backslash and ignore any commas that are in @(json|int|float|bool)(.+) format
        $args = preg_split('/(?<!\\\\),(?![^\(]*\))/', $input);

        $args = array_map(fn ($item) => str_replace('\,', ',', $item), $args);

        return $args;

    }

    /**
     * Parse string arguments from the command line.
     *
     * @param  string|null  $args  The arguments string to parse
     * @param  string|object  $class  The class or object instance
     * @param  string  $method  The method name
     * @return array<string, mixed> Parsed and cast arguments
     */
    protected function parseStringArguments(?string $args, string|object $class, string $method): array
    {
        if (empty($args)) {
            return [];
        }
        $this->debug("Parsing string: '{$method}: {$args}' for method and arguments.");

        $args = $this->splitArguments($args);

        return $this->nameAndCastArguments($class, $method, $args);

    }
}
