<?php

namespace App\Commands\Cache;

use App\Commands\BaseCommand as BaseCliCommand;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

abstract class BaseCommand extends BaseCliCommand
{
    /**
     * Get the cache store instance.
     */
    public function getCache(string $store = 'file')
    {
        try {
            $cache = Cache::store($store);
        } catch (InvalidArgumentException) {
            $this->components->error("The cache store '{$store}' is not supported.");
            exit(1);
        }

        return $cache;
    }
}
