# numbers

illuminate cli component for [numbers helper](https://laravel.com/docs/12.x/helpers#numbers) - [Docs](/docs/numbers.md)

## Use

You can use the `illuminate support:numbers` command to call `Illuminate\Support\Number` class methods using any abritrary options that will be treated as methods to call:

```bash

illuminate support:number 10 --spell
# returns "ten"
```

OR with argument:

```bash
./illuminate support:number 10 --spell="after:10"
# returns 10
```

## Syntax

Refer to [syntax documentation](/docs/syntax.md) for more details on the syntax and how to call methods, pass arguments, cast input and more.
