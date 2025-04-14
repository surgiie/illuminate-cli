# cache

illuminate cli component for [laravel cache](https://laravel.com/docs/11.x/cache)

## Supported Drivers

- Filesystem

**Note**: Pull requests are welcome to add more drivers supported by Laravel.

## Use

### Putting Data In The Cache

To put data in the cache, you can use the `put` command:

```bash
illuminate cache:put "value" --key="key"
# or with a expiration/ttl
illuminate cache:put "value" --key="key" --seconds=60

```

**Note** When `--key` is not provided, you will be prompted to enter the key.

### Getting Data From The Cache

To get data from the cache, you can use the `get` command:

```bash
illuminate cache:get "key"
```

### Checking if a Key Exists

To check if a key exists in the cache, you can use the `has` command:

```bash
illuminate cache:has "key"
```

The command will return `0` if the key does not exist, and `1` if it does. If you want a json response to process further, you can use the `--json` option:

```bash

illuminate cache:has "foo" --json
# returns:
# {
#    "key": 'foo'
#    "has": false
# }
```
