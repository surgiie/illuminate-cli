# encryption

illuminate cli component for [laravel encryption](https://laravel.com/docs/11.x/encryption).

## Use

### Generate encryption key

To generate a new encryption key, you can use the `illuminate encryption:key` command:

```bash
illuminate encryption:key
# returns base64 format of the key, e.g "base64:42wimtlACGsHVdxllsUEQ/tM3Z6Hjnp2hG31yhlMOh0=", this is the key you will pass to the encrypt and decrypt commands, store this somewhere safe
```

### Encrypting

To encrypt a string you can call the `encryption:encrypt` command with your string and the key:

```bash
illuminate encryption:encrypt "Hello, World!" --key="<key>"

# returns encrypted string in base64 format, e.g "eyJpdiI6Ikx6alRTc..."
```

### Decrypting

To decrypt a string you can call the `encryption:decrypt` command with your encrypted string and the key:

```bash
illuminate encryption:decrpy $encrypted_string --key="<key>"
```

### Options for specifying the key and cipher

You can specify the cipher using the `--cipher` option:

```bash
illuminate encryption:encrypt "Hello, World!" --key="<key>" --cipher=aes-128-cbc
```

You may also set the `ILLUMINATE_CLI_CIPHER` to avoid passing the `--cipher` option every time.

You can also set the `ILLUMINATE_CLI_ENCRYPTION_KEY` environment variable to avoid passing the `--key` option every time.

### Previous Keys

When rotating keys and you want the encrypter to attempt other keys when the current one fails, you can set the `ILLUMINATE_CLI_PREVIOUS_ENCRYPTION_KEYS` environment variable to a comma-separated list of previous keys. For example:

```bash
ILLUMINATE_CLI_PREVIOUS_ENCRYPTION_KEYS="base64:2nLsGFGzyoae2ax3EF2Lyq/hH6QghBGLIq5uL+Gp8/w=,base64:42wimtlACGsHVdxllsUEQ/tM3Z6Hjnp2hG31yhlMOh0="
```

**Note:** - Learn more [here](https://laravel.com/docs/11.x/encryption#gracefully-rotating-encryption-keys).
