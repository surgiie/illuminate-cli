<?php

namespace App\Commands\View;

use App\Commands\BaseCommand;
use App\Concerns\Helpers\LoadsEnvFiles;
use App\Concerns\Helpers\LoadsJsonFiles;
use App\Concerns\Validation\UsesValidator;
use App\Rules\Validation\IsFileRule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

use function Laravel\Prompts\text;

class MakeCommand extends BaseCommand
{
    use LoadsEnvFiles, LoadsJsonFiles, UsesValidator;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'view:make
                                {path? : The file path to render. }
                                {--require=* : Require a php file to autoload/execute scripts before render. }
                                {--save-to= : The custom file or directory path to save the rendered file(s) to. }
                                {--from-yaml=* : A yaml file path to load variable data from. }
                                {--from-json=* : A json file path to load variable data from. }
                                {--compiled-path= : Custom directory for the compiled cached files. }
                                {--from-env=* : A .env file to load variable data from. }
                                {--no-cache : Force recompile file & dont keep compiled file after render. }
                                {--force : Force render or overwrite existing files.}';

    /**
     * Determine if the command should have arbitrary options.
     */
    protected function shouldHaveArbitraryOptions(): bool
    {
        return true;
    }

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Render a file.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Determine the path to the file or directory of files to render.
        $givenPath = $this->argument('path') ?: text(
            label: 'What is the path to the file you want to render?',
            placeholder: '/home/example/template.yaml',
            required: true
        );

        $validator = $this->validator([
            'path' => $givenPath,
        ], [
            'path' => ['required', 'string', new IsFileRule],
        ]);

        if ($validator->fails()) {
            $this->fail($validator->errors()->first());
        }

        if ($this->option('compiled-path')) {
            config(['view.compiled' => $this->option('compiled-path')]);
        }

        $vars = $this->gatherVariables($this->arbitraryOptions->all());

        foreach ($this->option('require') as $file) {
            if (! file_exists($file)) {
                $this->fail("The require file '$file' does not exist.");
            }
            $vars = $this->requireFile($file, $vars);
        }

        $output = $this->renderFile(
            $givenPath,
            $vars,
        );

        $this->line($output);

        return 0;
    }

    /**
     * Render the given file using the given variables.
     */
    public function renderFile(string $path, array $vars = []): string|bool
    {
        $currentDirectory = getcwd();

        if ($noCache = $this->option('no-cache')) {
            config(['view.cache' => false]);
        }

        $path = realpath($path);
        // register the file's directory with the engine and change into it
        // so that file paths and the context of the file is relative.
        chdir($this->registerPossibleViewPath($path));

        try {
            $view = View::make($path, $vars);

            $contents = $view->render();

        } catch (\Throwable $e) {
            if (in_array(config('app.env'), ['development', 'testing'])) {
                throw $e;
            }
            $this->fail($e->getMessage());
        }

        if ($noCache) {
            @unlink($view->getEngine()->getCompiler()->getCompiledPath($path));
        }

        chdir($currentDirectory);

        return $contents;

    }

    /**
     * Compute the default save file name for the given file path.
     */
    protected function getDefaultSaveFileName(string $path): string
    {
        $info = new SplFileInfo($path);

        $basename = $info->getBasename('.'.$ext = $info->getExtension());

        if (strpos($basename, '.') === 0 && ".$ext" == $basename) {
            return $basename.'.rendered';
        } else {
            $basename .= '.rendered';
        }

        return $basename.($ext ? '.'.$ext : '');
    }

    /**
     * Register possible view path in the view's configuration for
     * the engine to be able to find the file in the given path
     * and returns the full directory of the file path.
     */
    public function registerPossibleViewPath(string $path): string
    {
        $directory = realpath(dirname($path));
        config(['view.paths' => array_merge(config('view.paths'), [$directory])]);

        return $directory;
    }

    /**
     * Normalize variable names to camel case.
     */
    protected function normalizeVariableNames(array $vars = []): array
    {
        $variables = [];
        foreach ($vars as $k => $value) {
            $variables[Str::camel(strtolower($k))] = $value;
        }

        return $variables;
    }

    /**
     * Gather the variables needed for rendering.
     */
    protected function gatherVariables(): array
    {
        $variables = [];

        foreach ($this->option('from-yaml', []) as $file) {
            $variables = array_merge($variables, Yaml::parseFile($file));
        }

        foreach ($this->option('from-json', []) as $file) {
            $variables = array_merge($variables, $this->loadJsonFile($file));
        }

        foreach ($this->option('from-env', []) as $file) {
            $variables = array_merge($variables, $this->getEnvFileVariables($file));
        }

        return $this->normalizeVariableNames(array_merge($variables, $this->arbitraryOptions->all()));
    }

    /**
     * Require a file and pass the variables to it.
     */
    protected function requireFile(string $path, array $variables = [])
    {
        $variables['__command'] = $this;

        extract($variables);

        $updatedVariables = require $path;

        unset($variables['__command']);

        return ! is_array($updatedVariables) ? $variables : array_merge($variables, $this->normalizeVariableNames($updatedVariables));
    }
}
