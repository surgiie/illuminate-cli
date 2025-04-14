# str

illuminate cli component for [laravel str](https://laravel.com/docs/12.x/strings#strings-method-list)

## Use

You can use the `illuminate support:str` command to call `Illuminate\Support\Str` class methods using any abritrary options that will be treated as methods to call. Depending on the method, it is invoked with or without a string argument:

```bash
illuminate support:str --password # Str::password() doesnt accept the string argument

# returns random password, e.g "wt$Sg$G6nH|Tvvj5u[i8B*4aRG/S-v#"
```

OR with argument:

```bash
illuminate support:str "This is my name" --before="my name" # Str::before("This is my name", "my name") does accept argument.
# returns "This is"
```

## Syntax

Refer to [syntax documentation](/docs/syntax.md) for more details on the syntax and how to call methods, pass arguments, cast input and more.
