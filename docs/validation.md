# validation

illuminate cli component for [laravel validation](https://laravel.com/docs/11.x/validation).

## Use

### Validating a Single Input

To validate a single string or input, you can pass the input as an argument followed by `--rules` to specify the validation rules:

```bash
illuminate validation:make "Foo" --rules="required|string|min:25"
```

This will fail with the following error:

```bash

# The argument input must be at least 25 characters.
```

### Validating Multiple Inputs

If you want to validate multiple inputs at once, pass them as arbitrary options. All arbitrary options will be parsed as inputs to the command. Each input can have its own validation rules specified using `--<input>-rules` options.

```bash
illuminate validation:make \
    --name="Ricky" \
    --name-rules="required|string|max:255" \
    --age="@int(25)" \
    --age-rules="required|int|min:18" \
```

### Return Errors as JSON

You can return validation errors as JSON by using the `--json` option. This is useful if you wish to pipe this to another command or tool, such as `jq`.

```bash

illuminate validation:make \
    --name="Ricky" \
    --name-rules="required|string|min:20" \
    --json

# returns
# {
#     "name": [
#         "The name input must be at least 20 characters."
#     ]
# }
```

## Syntax

Refer to [syntax documentation](/docs/syntax.md) for more details on the syntax and how to call methods, pass arguments, cast input and more.
