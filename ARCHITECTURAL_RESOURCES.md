<p align="center"><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/master/assets/GatoGraphQL-logo.webp"/></p>

![Unit tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/unit_tests.yml/badge.svg)<!-- @todo Re-enable executing integration tests for PROD in CI --><!-- @see https://github.com/GatoGraphQL/GatoGraphQL/issues/2253 --><!-- ![Integration tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/integration_tests.yml/badge.svg) -->
![Downgrade PHP tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/downgrade_php_tests.yml/badge.svg)
![Scoping tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/scoping_tests.yml/badge.svg)
![Generate plugins](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/generate_plugins.yml/badge.svg)
<!-- ![PHPStan](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/phpstan.yml/badge.svg) -->

# Gato GraphQL - Architectural resources

Here are docs explaining how Gato GraphQL was built.

## PHP Architecture

Articles explaining how the plugin is "downgraded", using PHP 8.1 for development but deployable to PHP 7.4 for production:

1. [Transpiling PHP code from 8.0 to 7.x via Rector](https://blog.logrocket.com/transpiling-php-code-from-8-0-to-7-x-via-rector/)
2. [Coding in PHP 7.4 and deploying to 7.1 via Rector and GitHub Actions](https://blog.logrocket.com/coding-in-php-7-4-and-deploying-to-7-1-via-rector-and-github-actions/)
3. [Tips for transpiling code from PHP 8.0 down to 7.1](https://blog.logrocket.com/tips-transpiling-code-from-php-8-0-to-7-1/)
4. [Including both PHP 7.1 and 8.0 code in the same plugin … or not?](https://blog.logrocket.com/including-php-7-1-and-8-0-code-same-plugin-or-not/)

Service container implementation:

- [Building extensible PHP apps with Symfony DI](https://blog.logrocket.com/building-extensible-php-apps-with-symfony-di/)

Explanation of how the codebase is split into granular packages, to enable CMS-agnosticism:

1. [Abstracting WordPress Code To Reuse With Other CMSs: Concepts (Part 1)](https://www.smashingmagazine.com/2019/11/abstracting-wordpress-code-cms-concepts/)
2. [Abstracting WordPress Code To Reuse With Other CMSs: Implementation (Part 2)](https://www.smashingmagazine.com/2019/11/abstracting-wordpress-code-reuse-with-other-cms-implementation/)

Description of how the plugin is scoped:

- [Gato GraphQL is now scoped, thanks to PHP-Scoper!](https://gatographql.com/blog/gatographql-is-now-scoped-thanks-to-php-scoper/)

## GraphQL by PoP documentation

Gato GraphQL is powered by the CMS-agnostic GraphQL server [GraphQL by PoP](https://graphql-by-pop.com).

Technical information on how the GraphQL server works:

- [GraphQL by PoP documentation](https://graphql-by-pop.com/docs/getting-started/intro.html).

Description of how a GraphQL server using server-side components works:

- [Implementing a GraphQL server with components in PHP](https://www.wpkube.com/implementing-graphql-server/)

These articles explain the concepts, design and implementation of GraphQL by PoP:

1. [Designing a GraphQL server for optimal performance](https://blog.logrocket.com/designing-graphql-server-optimal-performance/)
2. [Simplifying the GraphQL data model](https://blog.logrocket.com/simplifying-the-graphql-data-model/)
3. [Schema-first vs code-first development in GraphQL](https://blog.logrocket.com/code-first-vs-schema-first-development-graphql/)
4. [Speeding-up changes to the GraphQL schema](https://blog.logrocket.com/speeding-up-changes-to-the-graphql-schema/)
5. [Versioning fields in GraphQL](https://blog.logrocket.com/versioning-fields-graphql/)
6. [GraphQL directives are underrated](https://blog.logrocket.com/graphql-directives-are-underrated/)
7. [Treating GraphQL directives as middleware](https://blog.logrocket.com/treating-graphql-directives-as-middleware/) 
8. [Creating an @export GraphQL directive](https://blog.logrocket.com/creating-an-export-graphql-directive/)
9. [Adding directives to the schema in code-first GraphQL servers](https://blog.logrocket.com/adding-directives-schema-code-first-graphql-servers/)
10. [Coding a GraphQL server in JavaScript vs. WordPress](https://blog.logrocket.com/coding-a-graphql-server-in-javascript-vs-wordpress/)
11. [Supporting opt-in nested mutations in GraphQL](https://blog.logrocket.com/supporting-opt-in-nested-mutations-in-graphql/)
12. [HTTP caching in GraphQL](https://blog.logrocket.com/http-caching-graphql/)

## Gutenberg

These articles explain the integration with Gutenberg (the WordPress editor).

1. [Adding a Custom Welcome Guide to the WordPress Block Editor](https://css-tricks.com/adding-a-custom-welcome-guide-to-the-wordpress-block-editor/)
2. [Using Markdown and Localization in the WordPress Block Editor](https://css-tricks.com/using-markdown-and-localization-in-the-wordpress-block-editor/)
