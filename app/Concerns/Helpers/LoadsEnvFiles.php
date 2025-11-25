<?php

namespace App\Concerns\Helpers;

use Dotenv\Dotenv;
use Illuminate\Console\ManuallyFailedException;

trait LoadsEnvFiles
{
    /**
     * Parse a dot env file into an array of variables.
     *
     * @param  string  $path  Path to the .env file
     * @return array<string, string> Parsed environment variables
     *
     * @throws \Illuminate\Console\ManuallyFailedException
     */
    public function getEnvFileVariables(string $path): array
    {
        if (! is_file($path)) {
            throw new ManuallyFailedException("The env file '$path' does not exist.");
        }

        return Dotenv::parse(file_get_contents($path));
    }

    /**
     * Parse .env file and load it into the environment.
     *
     * @param  string  $path  Path to the .env file
     * @return array<string, string> Loaded environment variables
     *
     * @throws \Illuminate\Console\ManuallyFailedException
     */
    public function loadEnvFileVariables(string $path): array
    {
        if (! is_file($path)) {
            throw new ManuallyFailedException("The env file '$path' does not exist.");
        }

        $env = basename($path);

        $dotenv = Dotenv::createImmutable(dirname($path), $env);

        return $dotenv->load();
    }
}
