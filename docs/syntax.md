# Api Syntax

Most commands consist of accepting some type of argument and then a set of options to specify the methods to call on the input. Below is documentation

on the syntax that applies to most commands in this cli.

# Calling Methods

Commands accept arbitrary options to specify the methods to call on the input. The options are passed in the format of `--<method>="<args>"` where `<method>` is the method name and `<args>` are the arguments to pass to the method. The arguments can be passed as a comma separated list.

## Examples:

```bash

# No arguments
illuminate support:collection '["foo", "bar"]' --first

# returns "foo"
illuminate support:collection '{"a": 1, "b": 2}' --put="c,3"
# returns {"a": 1, "b": 2, "c": 3}

# escape comma if you dont want the command line to parse it
illuminate support:collection '{"a": 1, "b": 2}' --put="c,3\,d"
# returns {"a": 1, "b": 2, "c": 3,d}
```

**Note** - You can chain any number of methods together by passing multiple options. The order of the methods is important, as the output of one method is passed as the input to the next method. Note that
methods that accept callback functions are not supported. This functionality cannot be replicated from the command line.

### Casting/Encoding Input

Since all command line options are parsed as strings, a special syntax of `@<type>(<value>)` can be used to tell the command line to cast the value to a specific type. This is useful for methods that require specific types of arguments:

```bash
illuminate support:collection --range="@int(1),@int(3)"
# returns [1, 2, 3]
```

**Note** - Supported types are `int`, `float`, `null`, `bool`, and `json` (json_encode).

### Controlling Order Of Arguments

Depending on the class or component, the argument will automatically be passed in as the first parameter, for example:

```bash
illuminate support:str "This is my name" --before="my name" --before="is"
# returns "This"
```

The string is passed to each method as the first argument.

However, depending on the method and component class, the first argument may not always be the appropriate parameter for the argument. In these cases, named parameters are supported by specifying

the argument name in the format of `--<method>="<name>:<value>` where `<name>` is the name of the parameter and `<value>` is the value to pass to the parameter. For example, the `Str::is` method accepts the string as the the "value" parameter:

```bash
illuminate support:str foobar --is=pattern:foo\*,value:@value
```

The `@value` placeholder will be replaced with the value of the string being passed in. This lets you reference the value to pass when utilizing named arguments.
