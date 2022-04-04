# How transpiling works

Transpiling converts code from PHP 8.1 to 7.1. Then,

- The project uses PHP 8.1 for development
- The code is converted to PHP 7.1 when generating the plugins for production

For instance, PHP 8.0's union types, are converted [from this code](https://github.com/leoloso/PoP/blob/b9d379dc34195701e3afac2be4c132da6728ab75/layers/Schema/packages/custompost-mutations/src/TypeAPIs/CustomPostTypeAPIInterface.php#L18) in development:

```php
interface CustomPostTypeAPIInterface
{
  public function createCustomPost(array $data): string | int | null | Error;
}
```

into this code for production:

```php
interface CustomPostTypeAPIInterface
{
  public function createCustomPost(array $data)
}
```

Transpiling PHP code enables to use the latest PHP features for development, yet release the plugin with its code converted to an older PHP version for production, as to target a bigger user base.

## Additional resources

- [🦸🏿‍♂️ The GraphQL API for WordPress is now transpiled from PHP 8.0 to 7.1](https://graphql-api.com/blog/the-plugin-is-now-transpiled-from-php-80-to-71/)
- [Transpiling PHP code from 8.0 to 7.x via Rector](https://blog.logrocket.com/transpiling-php-code-from-8-0-to-7-x-via-rector/)
- [Coding in PHP 7.4 and deploying to 7.1 via Rector and GitHub Actions](https://blog.logrocket.com/coding-in-php-7-4-and-deploying-to-7-1-via-rector-and-github-actions/)
