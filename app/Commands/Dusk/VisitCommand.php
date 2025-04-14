<?php

namespace App\Commands\Dusk;

use App\Commands\BaseCommand;
use App\Concerns\Helpers\CallsMethods;
use App\Concerns\Validation\UsesValidator;
use BadMethodCallException;
use Illuminate\Console\ManuallyFailedException;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use NunoMaduro\LaravelConsoleDusk\ConsoleBrowser;
use PHPUnit\Framework\ExpectationFailedException;

class VisitCommand extends BaseCommand
{
    use CallsMethods, UsesValidator;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dusk:visit {url : The url to visit.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Visit site and perform actions and make assertions.';

    /**
     * Determine if the command should have arbitrary options.
     */
    protected function shouldHaveArbitraryOptions(): bool
    {
        return true;
    }

    /**
     * Configure the screenshot path.
     *
     * @param  string  $path
     * @return array
     */
    protected function configureScreenshot(array $arguments)
    {
        if (isset($arguments[0])) {
            Browser::$storeScreenshotsAt = dirname($arguments[0]);
        }

        return $arguments;
    }

    /**
     * Call an assertion/action method.
     */
    protected function callBrowserMethod(ConsoleBrowser $browser, string $method, string|array $option)
    {
        $value = is_string($option) ? $option : '';

        $method = Str::camel($method);
        $arguments = $this->parseActionArguments($value);

        // allow screenshots to be saved to a custom path
        if ($method == 'screenshot') {
            $arguments = $this->configureScreenshot($arguments);
        }

        try {
            $browser->$method(...$arguments);
        } catch (BadMethodCallException) {
            $this->components->error("Invalid browser action method: $method");
            exit(1);
        } catch (ExpectationFailedException|\TypeError $e) {
            // certain assertion/browser methods throw a TypeError, for now if this occurs, handle it until proper ExpectationFailedException is thrown
            // see: https://github.com/nunomaduro/laravel-console-dusk/issues/41
            if ($e instanceof \TypeError && ! Str::contains($e->getMessage(), "PHPUnit\TextUI\Configuration\Configuration, null returned")) {
                throw $e;
            }

            exit(1);
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $validator = $this->validator([
            'url' => $this->argument('url'),
        ], [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            $this->fail($validator->errors()->first());
        }

        $this->browse(function ($browser) {
            // see https://github.com/laravel/dusk/issues/781
            invade($browser)->browser->resolver->prefix = 'html';
            $browser = $browser->visit($this->argument('url'));

            foreach ($this->arbitraryOptionsOrdered as $option) {
                if ($option[1] === true || $option[1] == null) {
                    $browser = $this->callMethod($browser, $option[0]);

                    continue;
                }

                [$method, $arguments] = $this->parseStringMethodArguments($option[0].':'.$option[1]);
                try {

                    $browser = $this->callMethod($browser, $method, $arguments);
                } catch (ManuallyFailedException) {
                    // certain assertion/browser methods throw a TypeError, for now if this occurs, handle it until proper ExpectationFailedException is thrown
                    // see: https://github.com/nunomaduro/laravel-console-dusk/issues/41
                    $e = $this->failedCalledMethodException;

                    if ($e instanceof ExpectationFailedException || Str::contains($e->getMessage(), "PHPUnit\TextUI\Configuration\Configuration, null returned")) {
                        return 0;
                    }

                    throw $e;
                }
            }

        });

        return 0;
    }
}
