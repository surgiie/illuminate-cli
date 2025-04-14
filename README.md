# illuminate-cli

A command-line for various laravel illuminate or framework components.

![tests](https://github.com/surgiie/illuminate-cli/actions/workflows/tests.yml/badge.svg)

## Introduction

This package offers a command-line interface (CLI) for various Laravel framework components, making them available as standalone command line utilities.

Some components serve as simple wrappers around native functionality, while others extend or customize the original components to enhance features and improve functionality.

## Installation

You can install the package via composer:

```bash
composer global require "surgiie/illuminate-cli"
```

## Usage

```bash

illuminate --help

```

See [syntax documentation](/docs/syntax.md) and associated docs for each component in docs directory.

## Supported Components

- [Laravel\Dusk](https://laravel.com/docs/11.x/dusk) - [Docs](/docs/dusk.md)
- [Illuminate\View](https://laravel.com/docs/11.x/blade) - [Docs](/docs/view.md)
- [Illuminate\Cache](https://laravel.com/docs/11.x/validation) - [Docs](/docs/validation.md)
- [Illuminate\Support\Str](https://laravel.com/docs/11.x/strings) - [Docs](/docs/support/str.md)
- [Illuminate\Encryption](https://laravel.com/docs/11.x/encryption) - [Docs](/docs/encryption.md)
- [Illuminate\Validation](https://laravel.com/docs/11.x/validation) - [Docs](/docs/validation.md)
- [Illuminate\Support\Numbers](https://laravel.com/docs/12.x/helpers#numbers) - [Docs](/docs/numbers.md)
- [Illuminate\Support\Stringable](https://laravel.com/docs/11.x/strings) - [Docs](/docs/support/stringable.md)
- [Illuminate\Support\Collection](https://laravel.com/docs/11.x/collections) - [Docs](/docs/support/collection.md)

## Considerations

- Supported components does necessarily mean they are fully implemented or feature complete. If you find any issues, please open an issue. Some features for components might not be supported or replicated to the command line.
