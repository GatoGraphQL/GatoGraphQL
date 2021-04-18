# How scoping works

The plugin GraphQL API for WordPress is scoped using [PHP-Scoper](https://github.com/humbug/php-scoper).

PHP-Scoper [doesn't work well with WordPress](https://github.com/humbug/php-scoper/issues/303). However, this project can leverage the fine-grained splitting of packages in the repo, where packages are divided into 2 sets:

- Those containing the business-logic, and contracts for the CMS
- Those containing the implementation of contracts for WordPress

Then, the plugin can be scoped by avoiding those packages containing WordPress code, for which we must make sure these do not reference code from any external library (which should be scoped); we only scope the business-logic packages, which contain references to all external libraries but no WordPress code.

This way, some packages will be scoped and others will not, and they all form the plugin, tied together via Composer.

## Additional resources

- [PHP-Scoper](https://github.com/humbug/php-scoper)
- [🍾 GraphQL API for WordPress is now scoped, thanks to PHP-Scoper!](https://graphql-api.com/blog/graphql-api-for-wp-is-now-scoped-thanks-to-php-scoper/)
