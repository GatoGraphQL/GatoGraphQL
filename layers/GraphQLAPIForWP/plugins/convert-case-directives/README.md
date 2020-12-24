# Convert Case Directives for the GraphQL API

Directives to convert lower/title/upper case for the GraphQL API

## Usage

Coming soon...

## Development

Launch a WordPress environment with the GraphQL API plugin activated through [`wp-env`](https://www.npmjs.com/package/@wordpress/env).

[Prerequisites](https://www.npmjs.com/package/@wordpress/env#prerequisites):

- Node.js
- npm
- Docker

To [install `wp-env`](https://www.npmjs.com/package/@wordpress/env#installation) globally, run in the terminal:

```bash
npm -g i @wordpress/env
```

To start the WordPress environment, in the root folder of the plugin, execute:

```bash
wp-env start
```

The first time, this process can take a long time (half an hour or even more). To see what is happening, execute with the `--debug` option:

```bash
wp-env start --debug
```

Once finished, the website will be accessible under http://localhost:5475.

To access the wp-admin (under http://localhost:5475/wp-login.php):

- User: admin
- Password: password

## Credits

- [Leonardo Losoviz][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/leoloso
