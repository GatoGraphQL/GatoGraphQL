# Installing the GraphQL API for WordPress plugin

These are the several ways to install the [GraphQL API for WordPress](../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp) plugin.

## Requirements

- WordPress 5.4+
- PHP 7.1+

## Plugin

Download [the latest release of the plugin][latest-release-url] as a .zip file.

Then, in the WordPress admin:

- Go to `Plugins => Add New`
- Click on `Upload Plugin`
- Select the .zip file
- Click on `Install Now` (it may take a few minutes)
- Once installed, click on `Activate`

### Timeout in Nginx?

Nginx has a time limit to process the response from the PHP backend, and installing large WordPress plugins may exceed the default time limit.

If when installing the plugin you get a "504 Gateway Timeout" error, or an entry `"upstream timed out (110: Connection timed out) while reading upstream"` in the log, increase the timeout to `300` seconds in the Nginx config, as [explained here](https://wordpress.org/support/topic/504-gateway-time-out-504-gateway-time-out-nginx/#post-13423918).

## Composer

Add the following configuration to your `composer.json`:

```json
{
    "require": {
        "graphql-api/graphql-api-for-wp": "^0.9.0"
    },
    "minimum-stability": "dev",
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "graphql-api/graphql-api-for-wp",
                "type": "wordpress-plugin",
                "version": "0.9.0",
                "dist": {
                    "url": "https://github.com/leoloso/PoP/releases/latest/download/graphql-api.zip",
                    "type": "zip"
                },
                "require": {
                    "composer/installers": "^1"
                }
            }
        }
    ],
    "extra": {
        "installer-paths": {
            "wp-content/plugins/{$name}/": [
                "type:wordpress-plugin"
            ]
        }
    }
}
```

## WP-CLI

To install via [WP-CLI](http://wp-cli.org/), execute this command:

```bash
wp plugin install --activate https://github.com/leoloso/PoP/releases/latest/download/graphql-api.zip
```

## GitHub Updater

This plugin support automatic updating via the [GitHub Updater](https://github.com/afragen/github-updater).

[latest-release-url]: https://github.com/leoloso/PoP/releases/latest/download/graphql-api.zip
