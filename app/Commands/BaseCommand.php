<?php

namespace App\Commands;

use Closure;
use Laravel\Prompts\Spinner;
use Laravel\Tinker\ClassAliasAutoloader;
use Psy\Configuration;
use Psy\Shell;
use Surgiie\ArtisanArbitraryOptions\Command;

use function Termwind\render;
use function Termwind\renderUsing;

abstract class BaseCommand extends Command
{
    /**
     * Determine if the command should have arbitrary options.
     */
    protected function shouldHaveArbitraryOptions(): bool
    {
        return false;
    }

    /**
     * Start a Psy Shell (tinker) session with the given variables.
     *
     * @param  array<string, mixed>  $vars  Variables to make available in the shell
     * @return array<string, mixed> Updated variables after shell session
     */
    protected function tinker(array $vars = []): array
    {
        $config = new Configuration(['updateCheck' => 'never']);
        $shell = new Shell($config);

        $loader = ClassAliasAutoloader::register($shell, $this->getLaravel()->basePath().'/vendor/composer/autoload_classmap.php');

        $shell->setScopeVariables($vars);
        try {
            $shell->run();

            return $shell->getScopeVariables();
        } finally {
            $loader->unregister();
        }

    }

    /**
     * Print a debug message if the command has a --debug option and it is passed.
     *
     * @param  string  $message  The debug message to display
     */
    protected function debug(string $message): void
    {
        if ($this->hasOption('debug') && $this->option('debug')) {
            renderUsing($this->output);
            render(<<<"HTML"
            <div class="mx-2 mb-1 mt-1">
                <span class="px-1 bg-white text-black uppercase">DEBUG</span>
                <span class="ml-1 text-white">
                    <strong class="text-white">$message</strong>
                </span>
            </div>
            HTML);
        }
    }

    /**
     * Run a long running task with a spinner.
     *
     * @param  string  $title  The title to display while task is running
     * @param  \Closure|null  $task  The task closure to execute
     * @param  string  $finishedText  Text to display when task completes
     * @param  bool  $spinner  Whether to show animated spinner
     * @return mixed The result of the task closure
     */
    public function runTask(string $title = '', ?Closure $task = null, string $finishedText = '', bool $spinner = false)
    {
        $finishedText = $finishedText ?: $title;

        if ($spinner) {
            $result = (new Spinner($title))->spin(
                $task,
                $title,
            );
        } else {
            $result = invade((new Spinner($title)))->renderStatically($task);
        }

        $this->output->writeln(
            "  $finishedText: ".($result !== false ? '<info>Success</info>' : '<error>Failed</error>')
        );

        return $result;
    }
}
