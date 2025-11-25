<?php

namespace App\Concerns\Helpers;

use Illuminate\Console\ManuallyFailedException;

trait LoadsJsonFiles
{
    /**
     * Return a more human readable error message for JSON errors.
     *
     * @param  string  $error  The JSON error code
     * @return string Human-readable error message
     */
    protected function formatJsonParseError(string $error): string
    {
        switch ($error) {
            case JSON_ERROR_DEPTH:
                return 'JSON Error - Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'JSON Error - Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'JSON Error - Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'JSON Error - Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'JSON Error - Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return 'JSON Error - Unknown error';
        }
    }

    /**
     * Load a JSON file and return the decoded data.
     *
     * @param  string  $path  Path to the JSON file
     * @param  int  $options  JSON decode options
     * @return array<string, mixed> Decoded JSON data
     *
     * @throws \Illuminate\Console\ManuallyFailedException
     */
    public function loadJsonFile(string $path, $options = JSON_OBJECT_AS_ARRAY): array
    {
        if (! is_file($path)) {
            throw new ManuallyFailedException("The json file '$path' does not exist.");
        }

        $data = json_decode(file_get_contents($path), $options);

        $error = json_last_error();

        if ($error !== JSON_ERROR_NONE) {
            throw new ManuallyFailedException($this->formatJsonParseError(json_last_error()));
        }

        return $data;
    }
}
