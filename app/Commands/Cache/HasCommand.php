<?php

namespace App\Commands\Cache;

class HasCommand extends BaseCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'cache:has
                                    {key : The key to get from the cache.}
                                    {--store=file : The cache store to use.}
                                    {--json : Output the result as json.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Check if cache has an item.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $key = $this->argument('key');
        $store = $this->option('store');
        $cache = $this->getCache($store);

        $status = 0;
        $json = ['key' => $key, 'has' => $cache->has($key)];
        if (! $json['has']) {
            $status = 1;
        }

        if ($this->option('json')) {
            $this->line(json_encode($json, JSON_PRETTY_PRINT));
        } elseif (! $json['has']) {
            $this->components->error("Cache does not have item with key '$key'");
        } else {
            $this->components->success("Cache has item with key '$key'");
        }

        return $status;
    }
}
