<?php

namespace App\Extensions\View;

use Illuminate\View\FileViewFinder as BaseFileViewFinder;
use InvalidArgumentException;

class FileViewFinder extends BaseFileViewFinder
{
    /**
     * Get the possible view files for the given name.
     *
     * @param  string  $name
     * @return array
     */
    protected function getPossibleViewFiles($name)
    {
        return [$name];
    }

    /**
     * Find the view in registered paths.
     *
     * @param  string  $name
     * @param  array  $paths
     * @return void
     */
    protected function findInPaths($name, $paths)
    {
        try {

            return parent::findInPaths($name, $paths);
        } catch (InvalidArgumentException) {
            if (file_exists($name)) {
                return $name;
            }

            throw new InvalidArgumentException("File [{$name}] not found.");
        }
    }
}
