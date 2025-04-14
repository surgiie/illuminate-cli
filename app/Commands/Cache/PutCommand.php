<?php

namespace App\Commands\Cache;

use App\Concerns\Validation\UsesValidator;

use function Laravel\Prompts\text;

class PutCommand extends BaseCommand
{
    use UsesValidator;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'cache:put
                                    {data : The data to put in the cache.}
                                    {--key= : The key to store the data under.}
                                    {--seconds= : The time to live in seconds for the cache item.}
                                    {--store=file : The cache store to use.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Put an item in cache.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $validator = $this->validator(['seconds' => $this->option('seconds')], ['seconds' => 'nullable|integer']);
        if ($validator->fails()) {
            $this->components->error($validator->errors()->first());

            return 1;
        }

        $key = $this->option('key') ?: text(
            label: 'Enter a key to store the data under',
        );

        $store = $this->option('store');
        $cache = $this->getCache($store);

        $cache->put($key, $this->argument('data'), $this->option('seconds'));

        if ($seconds = $this->option('seconds')) {
            $this->components->success("Item put in cache under key '{$key}' with a TTL of {$seconds} seconds");
        } else {
            $this->components->success("Item put in cache under key '{$key}'");
        }

        return 0;
    }
}
