# view

illuminate cli component for [laravel blade/view](https://laravel.com/docs/11.x/blade).

**Note:** This component is extended to allow rendering any text file, not just html views.

## Use

To render a file, you can call the `view:make` command and pass any arbitrary options to use as variable data for your file. For example, lets say you have the following file named `person.yml`:

```yaml
name: {{ $name }}
relationship: {{ $relationship }}
favorite_food: {{ $favoriteFood }}
@if($includeAddress)
address: 123 example lane
@endif
```

You can render the file like so:

```bash
illuminate view:make person.yml \
                --name="Bob" \
                --relationship="Uncle" \
                --favorite-food="Pizza" \
                --include-address
```

This will render the file and output the following contents:

```yaml
name: Bob
relationship: Uncle
favorite_food: Pizza
address: 123 example lane
```

You can of course redirect the output to a file.

## Variable Data

There are three options for passing variable data to your files being rendered, in order of precedence:

- Use YAML files with the `--from-yaml` option and pass a path to the file.
- Use JSON files with the `--from-json` option and pass a path to the file.
- Use env files with the `--from-env` option and pass a path to the .env file.
- Use arbitrary command line options with the render command, like `--example-var=value`.

**Note** - Since command line options are kebab-case, and php doesnt support hyphens in variable names, all variables coming from command line options or files will be normalized to camelCase. For example, `--example-var=value` will be available as `$exampleVar` in your files.

### Command Line Variable Types

The following types of variables are currently supported:

- String/Single Value Variables: Use a single option key/value format, e.g. `--foo=bar --bar=baz`
- Array Value Variables: Pass the option multiple times, e.g. `--names=Steve --names=Ricky --names=Bob`
- True Boolean Value Variables: Pass the option with no value, e.g. `--should-do-thing`

## Compiled Directory Locations

- If set, the env `ILLUMINATE_CLI_VIEW_COMPILED_PATH` will be used.
- If using the standalone/phar build of the cli: `/tmp/.illuminate-cli/compiled`
- If cloned and calling binary manually, the compiled files are stored in the `storage/framework/compiled` directory.

### Clean Cached Compiled Directory

If you are working with large files or have a lot of files to render, the compiled files directory can grow quite large, consider cleaning it regularly.

To clean the cached/compiled files directory of files older than 24 hours, use the `cache:clean` command:

```bash
illuminate view:clear-compiled
# or delete only if older than given minutes
illuminate view:clear-compiled --minutes=60
```

## Require Files Before Rendering

If you have custom code or logic you need to execute before rendering a file, you can use the `--require` option to require a file before rendering. This can be useful for loading custom classes or functions that can be used in your files. For convenience, a special `$__command` variable is available in the required file which contains the command instance, this can be useful for accessing the command's options and arguments or if you need output text to the console. Your variable data will also be available in the required file. You may also find the need to mutate the variable data before rendering, you can do this in the required file by returning the mutated variables in an array to be merged into the existing variable data.

For example, if you have a file named `required-file.php` with the following content:

```bash
larave-blade render template.yaml --name="Bob" --require="required-file.php"
```

```php
<?php

if($name == "Bob") {
    $name = "Uncle Bob";
}

// do more stuff

$__command->info("Did stuff!");

// return mutated variables, will be merged to existing variables
// if no mutation is needed, the return statement is not necessary
return [
    "name" => $name,
];

```

### X-Blade Components

If for some reason you need to use anonymous rendered components or class based components, they are supported but you are responsible for registering your components before rendering.

To allow this, you can utilize the `--require` option to require a file that registers your components. For example, if you have a file named `register-components.php` with the following content:

```php

<?php
// require class or file that loads the classes, such as composer autoload file
require_once "somefilewithclasses.php";
// register <x-example> component for class
Blade::component('example', YourExampleComponent::class);
// or specify path to components that can be resolved by file anonymously
$prefix = null; // set if desired
Blade::anonymousComponentPath(realpath([__DIR__.'/components']), $prefix);
```

You can then render your file like so:

```bash

illuminate view:make template.yaml --name="Bob" --require="register-components.php"
```

and your files should be able to utilize the components like normal:

```html
<x-example :name="$name" />
```
