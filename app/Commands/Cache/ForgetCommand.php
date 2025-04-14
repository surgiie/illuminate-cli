<?php

namespace App\Commands\Cache;

class ForgetCommand extends BaseCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'cache:forget
                                    {key : The key to get from the cache.}
                                    {--store=file : The store to get the item from.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Forget an item in the cache.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $key = $this->argument('key');
        $store = $this->option('store');

        $cache = $this->getCache($store);
        if (! $cache->has($key)) {
            $this->components->error("The item with key '{$key}' does not exist in the cache.");

            return 1;
        }

        $result = $cache->forget($key);

        if (! $result) {
            $this->components->error("The item with key '{$key}' could not be forgotten.");

            return 1;
        }

        $this->components->success("The item with key '{$key}' has been forgotten.");

        return 0;
    }
}
