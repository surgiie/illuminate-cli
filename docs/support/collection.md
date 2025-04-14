# collection

illuminate cli component for [laravel collection](https://laravel.com/docs/12.x/collections)

## Use

To parse json string to collection, you can pass the json string as an argument and chain any arbritrary option to specify the methods to call on the collection:

```bash

illuminate support:collection '["foo", "bar"]' --first

# returns "foo"
```

```bash
illuminate support:collection '{"a": 1, "b": 2}' --put="c,3"
# returns {"a": 1, "b": 2, "c": 3}
```

## Syntax

Refer to [syntax documentation](/docs/syntax.md) for more details on the syntax and how to call methods, pass arguments, cast input and more.
