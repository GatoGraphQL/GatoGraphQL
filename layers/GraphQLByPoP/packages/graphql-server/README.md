# GraphQL server

<!-- [![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md) -->

<!--
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

GraphQL server in PHP, implemented through the PoP API

## Install

### Installing the GraphQL server

Follow the instructions in the [GraphQL by PoP installation page](https://graphql-by-pop.com/docs/getting-started/installation/).

### Installing the library in a PoP application

Via Composer

``` bash
composer require graphql-by-pop/graphql-server
```

To enable pretty API endpoint `/api/graphql/`, follow the instructions [here](https://github.com/getpop/api#enable-pretty-permalinks)

<!-- > Note: if you wish to install a fully-working API, please follow the instructions under [Bootstrap a PoP API for WordPress](https://github.com/leoloso/PoP-API-WP) (even though CMS-agnostic, only the WordPress adapters have been presently implemented). -->

<!--
### Enable pretty permalinks

Add the following code in the `.htaccess` file to enable API endpoint `/api/graphql/`:

```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /

# Rewrite from /some-url/api/graphql/ to /some-url/?scheme=api&datastructure=graphql
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^(.*)/api/graphql/?$ /$1/?scheme=api&datastructure=graphql [L,P,QSA]

# b. Homepage single endpoint (root)
# Rewrite from api/graphql/ to /?scheme=api&datastructure=graphql
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^api/graphql/?$ /?scheme=api&datastructure=graphql [L,P,QSA]
</IfModule>
```
-->

## Usage

Initialize the component:

``` php
\PoP\Root\ComponentLoader::initializeComponents([
    \GraphQLByPoP\GraphQLServer\Component::class,
]);
```

## 100% compliant of GraphQL syntax

All [GraphQL queries](https://graphql.org/learn/queries/) are supported (click on the links below to try them out in [GraphiQL](https://newapi.getpop.org/graphiql/)):

- <a href="https://newapi.getpop.org/graphiql/?query=query%20%7B%0A%20%20posts%20%7B%0A%20%20%20%20id%0A%20%20%20%20url%0A%20%20%20%20title%0A%20%20%20%20excerpt%0A%20%20%20%20date%0A%20%20%20%20tags%20%7B%0A%20%20%20%20%20%20name%0A%20%20%20%20%7D%0A%20%20%20%20comments%20%7B%0A%20%20%20%20%20%20content%0A%20%20%20%20%20%20author%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20name%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D" target="leoloso-graphiql">Fields</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20%7B%0A%20%20posts(limit%3A2)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20posts(limit%3A3)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20date(format%3A%22d%2Fm%2FY%22)%0A%20%20%20%20%20%20%20%20tags%20%7B%0A%20%20%20%20%20%20%20%20%20%20name%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20featuredImage%20%7B%0A%20%20%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20%20%20src%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D" target="leoloso-graphiql">Field arguments</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A2)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A3)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%20%20formattedDate%3A%20date(format%3A%22d%2Fm%2FY%22)%0A%20%20%20%20%20%20%20%20tags%20%7B%0A%20%20%20%20%20%20%20%20%20%20name%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20featuredImage%20%7B%0A%20%20%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20%20%20src%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D" target="leoloso-graphiql">Aliases</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A2)%20%7B%0A%20%20%20%20...postProperties%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A3)%20%7B%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20...postProperties%0A%20%20%20%20%20%20%20%20formattedDate%3A%20date(format%3A%22d%2Fm%2FY%22)%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0Afragment%20postProperties%20on%20Post%20%7B%0A%20%20id%0A%20%20title%0A%20%20tags%20%7B%0A%20%20%20%20name%0A%20%20%7D%0A%7D" target="leoloso-graphiql">Fragments</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20GetPosts%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A2)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D&operationName=GetPosts" target="leoloso-graphiql">Operation name</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20GetPosts(%24rootLimit%3A%20Int%2C%20%24nestedLimit%3A%20Int%2C%20%24dateFormat%3A%20String)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A%24rootLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A%24nestedLimit)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%20%20formattedDate%3A%20date(format%3A%24dateFormat)%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D&operationName=GetPosts&variables=%7B%0A%20%20%22rootLimit%22%3A%203%2C%0A%20%20%22nestedLimit%22%3A%202%2C%0A%20%20%22dateFormat%22%3A%20%22d%2Fm%2FY%22%0A%7D" target="leoloso-graphiql">Variables</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20GetPosts(%24tagsLimit%3A%20Int)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A2)%20%7B%0A%20%20%20%20...postProperties%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A3)%20%7B%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20...postProperties%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0Afragment%20postProperties%20on%20Post%20%7B%0A%20%20id%0A%20%20title%0A%20%20tags(limit%3A%24tagsLimit)%20%7B%0A%20%20%20%20name%0A%20%20%7D%0A%7D&operationName=GetPosts&variables=%7B%0A%20%20%22tagsLimit%22%3A%203%0A%7D" target="leoloso-graphiql">Variables inside fragments</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20GetPosts(%24rootLimit%3A%20Int%20%3D%203%2C%20%24nestedLimit%3A%20Int%20%3D%202%2C%20%24dateFormat%3A%20String%20%3D%20%22d%2Fm%2FY%22)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A%24rootLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A%24nestedLimit)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%20%20formattedDate%3A%20date(format%3A%24dateFormat)%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D&operationName=GetPosts" target="leoloso-graphiql">Default variables</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20GetPosts(%24includeAuthor%3A%20Boolean!%2C%20%24rootLimit%3A%20Int%20%3D%203%2C%20%24nestedLimit%3A%20Int%20%3D%202)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A%24rootLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%40include(if%3A%20%24includeAuthor)%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A%24nestedLimit)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D&operationName=GetPosts&variables=%7B%0A%20%20%22includeAuthor%22%3A%20true%0A%7D" target="leoloso-graphiql">Directives</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20GetPosts(%24includeAuthor%3A%20Boolean!%2C%20%24rootLimit%3A%20Int%20%3D%203%2C%20%24nestedLimit%3A%20Int%20%3D%202)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A%24rootLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20...postProperties%0A%20%20%7D%0A%7D%0Afragment%20postProperties%20on%20Post%20%7B%0A%20%20author%20%40include(if%3A%20%24includeAuthor)%20%7B%0A%20%20%20%20id%0A%20%20%20%20name%0A%20%20%20%20nestedPosts%3A%20posts(limit%3A%24nestedLimit)%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20url%0A%20%20%20%20%20%20title%0A%20%20%20%20%20%20date%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D&operationName=GetPosts&variables=%7B%0A%20%20%22includeAuthor%22%3A%20true%0A%7D" target="leoloso-graphiql">Fragments with directives</a>
- <a href="https://newapi.getpop.org/graphiql/?query=query%20GetPosts(%24rootLimit%3A%20Int%20%3D%203%2C%20%24nestedLimit%3A%20Int%20%3D%202)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A%24rootLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20customPosts(limit%3A%24nestedLimit)%20%7B%0A%20%20%20%20%20%20%20%20__typename%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20...%20on%20Post%20%7B%0A%20%20%20%20%20%20%20%20%20%20excerpt%0A%20%20%20%20%20%20%20%20%20%20tags%20%7B%0A%20%20%20%20%20%20%20%20%20%20%20%20name%0A%20%20%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20...%20on%20Page%20%7B%0A%20%20%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D&operationName=GetPosts" target="leoloso-graphiql">Inline fragments</a>

GraphQL by PoP supports query batching. This query contains all queries from above:

- <a href="https://newapi.getpop.org/graphiql/?query=query%20FieldsExample%20%7B%0A%20%20posts%20%7B%0A%20%20%20%20id%0A%20%20%20%20url%0A%20%20%20%20title%0A%20%20%20%20excerpt%0A%20%20%20%20date%0A%20%20%20%20tags%20%7B%0A%20%20%20%20%20%20name%0A%20%20%20%20%7D%0A%20%20%20%20comments%20%7B%0A%20%20%20%20%20%20content%0A%20%20%20%20%20%20author%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20name%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0A%0Aquery%20FieldArgumentsExample%20%7B%0A%20%20twoposts%3Aposts(limit%3A2)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20posts(limit%3A3)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20formattedDate%3Adate(format%3A%22d%2Fm%2FY%22)%0A%20%20%20%20%20%20%20%20tags%20%7B%0A%20%20%20%20%20%20%20%20%20%20name%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20featuredImage%20%7B%0A%20%20%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20%20%20src%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0A%0Aquery%20AliasesExample%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A2)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A3)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%20%20formattedDate%3A%20date(format%3A%22d%2Fm%2FY%22)%0A%20%20%20%20%20%20%20%20tags%20%7B%0A%20%20%20%20%20%20%20%20%20%20name%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20featuredImage%20%7B%0A%20%20%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20%20%20src%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0A%0Aquery%20FragmentsExample%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A2)%20%7B%0A%20%20%20%20...postProperties%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A3)%20%7B%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20...postProperties%0A%20%20%20%20%20%20%20%20formattedDate%3A%20date(format%3A%22d%2Fm%2FY%22)%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0Afragment%20postProperties%20on%20Post%20%7B%0A%20%20id%0A%20%20title%0A%20%20tags%20%7B%0A%20%20%20%20name%0A%20%20%7D%0A%7D%0A%0Aquery%20VariablesExample(%24rootLimit%3A%20Int%2C%20%24nestedLimit%3A%20Int%2C%20%24dateFormat%3A%20String)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A%24rootLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A%24nestedLimit)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%20%20formattedDate%3A%20date(format%3A%24dateFormat)%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0A%0Aquery%20VariablesInsideFragmentsExample(%24tagsLimit%3A%20Int)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A2)%20%7B%0A%20%20%20%20...varInFragPostProperties%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A3)%20%7B%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20...varInFragPostProperties%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0Afragment%20varInFragPostProperties%20on%20Post%20%7B%0A%20%20id%0A%20%20title%0A%20%20tags(limit%3A%24tagsLimit)%20%7B%0A%20%20%20%20name%0A%20%20%7D%0A%7D%0A%0Aquery%20DefaultVariablesExample(%24someLimit%3A%20Int%20%3D%203%2C%20%24anotherLimit%3A%20Int%20%3D%202%2C%20%24someDateFormat%3A%20String%20%3D%20%22d%2Fm%2FY%22)%20%7B%0A%20%20varRootPosts%3A%20posts(limit%3A%24someLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A%24anotherLimit)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%20%20formattedDate%3A%20date(format%3A%24someDateFormat)%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0A%0Aquery%20DirectivesExample(%24includeAuthor%3A%20Boolean!%2C%20%24giveLimit%3A%20Int%20%3D%203%2C%20%24nextLimit%3A%20Int%20%3D%202)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A%24giveLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%40include(if%3A%20%24includeAuthor)%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20nestedPosts%3A%20posts(limit%3A%24nextLimit)%20%7B%0A%20%20%20%20%20%20%20%20id%0A%20%20%20%20%20%20%20%20url%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0A%0Aquery%20FragmentsWithDirectivesExample(%24printAuthor%3A%20Boolean!%2C%20%24fragRootLimit%3A%20Int%20%3D%203%2C%20%24fragNestedLimit%3A%20Int%20%3D%202)%20%7B%0A%20%20rootPosts%3A%20posts(limit%3A%24fragRootLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20...fragWithDirecPostProperties%0A%20%20%7D%0A%7D%0Afragment%20fragWithDirecPostProperties%20on%20Post%20%7B%0A%20%20author%20%40include(if%3A%20%24printAuthor)%20%7B%0A%20%20%20%20id%0A%20%20%20%20name%0A%20%20%20%20nestedPosts%3A%20posts(limit%3A%24fragNestedLimit)%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20url%0A%20%20%20%20%20%20title%0A%20%20%20%20%20%20date%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D%0A%0Aquery%20InlineFragmentsExample(%24inlineLimit%3A%20Int%20%3D%203%2C%20%24customLimit%3A%20Int%20%3D%202)%20%7B%0A%20%20inlinePosts%3A%20posts(limit%3A%24inlineLimit)%20%7B%0A%20%20%20%20id%0A%20%20%20%20title%0A%20%20%20%20author%20%7B%0A%20%20%20%20%20%20id%0A%20%20%20%20%20%20name%0A%20%20%20%20%20%20customPosts(limit%3A%24customLimit)%20%7B%0A%20%20%20%20%20%20%20%20__typename%0A%20%20%20%20%20%20%20%20title%0A%20%20%20%20%20%20%20%20...%20on%20Post%20%7B%0A%20%20%20%20%20%20%20%20%20%20excerpt%0A%20%20%20%20%20%20%20%20%20%20tags%20%7B%0A%20%20%20%20%20%20%20%20%20%20%20%20name%0A%20%20%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20...%20on%20Page%20%7B%0A%20%20%20%20%20%20%20%20%20%20date%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%0A%20%20%7D%0A%7D&operationName=FragmentsWithDirectivesExample&variables=%7B%0A%20%20%22rootLimit%22%3A%203%2C%0A%20%20%22nestedLimit%22%3A%202%2C%0A%20%20%22dateFormat%22%3A%20%22d%2Fm%2FY%22%2C%0A%20%20%22tagsLimit%22%3A%203%2C%0A%20%20%22includeAuthor%22%3A%20true%2C%0A%20%20%22printAuthor%22%3A%20true%0A%7D">Query batching</a>

## Extended GraphQL

An upgraded implementation of the GraphQL server, which enables to resolve [queries as a scripting language](https://leoloso.com/posts/demonstrating-pop-api-graphql-on-steroids/), is found under [this repo](https://github.com/getpop/api-graphql).

It supports several [features](https://leoloso.com/posts/pop-api-features/) not currently defined by the GraphQL spec, including [composable fields](https://github.com/getpop/api-graphql#composable-fields) and [composable directives](https://github.com/getpop/api-graphql#composable-directives).

## Support for REST

By installing the [REST package](https://github.com/getpop/api-rest), the GraphQL server can also satisfy REST endpoints, from a single source of truth. Check out these example links:

- [List of posts](https://newapi.getpop.org/posts/api/rest/)
- [Single post](https://newapi.getpop.org/posts/cope-with-wordpress-post-demo-containing-plenty-of-blocks/api/rest/)

## Demo

The GraphQL API (running on top of [a WordPress site](https://newapi.getpop.org)) is deployed under this endpoint: https://newapi.getpop.org/api/graphql/

You can play with it through the following clients: 

- GraphiQL: https://newapi.getpop.org/graphiql/
- GraphQL Voyager: https://newapi.getpop.org/graphql-interactive/

## PHP versions

Requirements:

- PHP 7.4+ for development
- PHP 7.1+ for production

### Supported PHP features

| PHP Version | Features |
| --- | --- |
| 7.1 | Everything |
| 7.2 | [`object` type](https://www.php.net/manual/en/migration72.new-features.php#migration72.new-features.object-type)<br/><br/>[parameter type widening](https://www.php.net/manual/en/migration72.new-features.php#migration72.new-features.param-type-widening)<br/><br/>Functions:<ul><li>[`spl_object_id`](https://php.net/spl_object_id)</li><li>[`utf8_encode`](https://php.net/utf8_encode)</li><li>[`utf8_decode`](https://php.net/utf8_decode)</li></ul>Constants:<ul><li>[`PHP_FLOAT_*`](https://php.net/reserved.constants#constant.php-float-dig)</li><li>[`PHP_OS_FAMILY`](https://php.net/reserved.constants#constant.php-os-family)</li></ul> |
| 7.3 | [Reference assignments in `list()`/array destructuring](https://www.php.net/manual/en/migration73.new-features.php#migration73.new-features.core.destruct-reference) => `[&$a, [$b, &$c]] = $d`<br/>_Except inside `foreach` ([#4376](https://github.com/rectorphp/rector/issues/4376))_<br/><br/>[Flexible Heredoc and Nowdoc syntax](https://www.php.net/manual/en/migration73.new-features.php#migration73.new-features.core.heredoc)<br/><br/>[Trailing commans in functions calls](https://www.php.net/manual/en/migration73.new-features.php#migration73.new-features.core.trailing-commas)<br/><br/>[`set(raw)cookie` accepts $option argument](https://www.php.net/manual/en/migration73.other-changes.php#migration73.other-changes.core.setcookie)<br/><br/>Functions:<ul><li>[`array_key_first`](https://php.net/array_key_first)</li><li>[`array_key_last`](https://php.net/array_key_last)</li><li>[`hrtime`](https://php.net/function.hrtime)</li><li>[`is_countable`](https://php.net/is_countable)</li></ul>Exceptions:<ul><li>[`JsonException`](https://php.net/JsonException)</li></ul> |
| 7.4 | [Typed properties](https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.typed-properties)<br/><br/>[Arrow functions](https://www.php.net/manual/en/functions.arrow.php)<br/><br/>[Null coalescing assignment operator](https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.null-coalescing-assignment-operator) => `??=`<br/><br/>[Unpacking inside arrays](https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.unpack-inside-array) => `$nums = [3, 4]; $merged = [1, 2, ...$nums, 5];`<br/><br/>[Numeric literal separator](https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.numeric-literal-separator) => `1_000_000`<br/><br/>[`strip_tags()` with array of tag names](https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.standard.strip-tags) => `strip_tags($str, ['a', 'p'])`<br/><br/>[covariant return types and contravariant param types](https://www.php.net/manual/en/migration74.new-features.php#migration74.new-features.core.type-variance)<br/><br/>Functions:<ul><li>[`get_mangled_object_vars`](https://php.net/get_mangled_object_vars)</li><li>[`mb_str_split`](https://php.net/mb_str_split)</li><li>[`password_algos`](https://php.net/password_algos)</li></ul> |
<!-- @todo Uncomment when PHP 8.0 released -->
<!--
| 8.0 | [Union types](https://php.watch/versions/8.0/union-types)<br/><br/>[`mixed` pseudo type](https://php.watch/versions/8.0#mixed-type)<br/><br/>[`static` return type](https://wiki.php.net/rfc/static_return_type)<br/><br/>Interfaces:<ul><li>`Stringable`</li></ul>Classes:<ul><li>`ValueError`</li><li>`UnhandledMatchError`</li></ul>Constants:<ul><li>`FILTER_VALIDATE_BOOL`</li></ul>Functions:<ul><li>[`fdiv`](https://php.net/fdiv)</li><li>[`get_debug_type`](https://php.net/get_debug_type)</li><li>[`preg_last_error_msg`](https://php.net/preg_last_error_msg)</li><li>[`str_contains`](https://php.net/str_contains)</li><li>[`str_starts_with`](https://php.net/str_starts_with)</li><li>[`str_ends_with`](https://php.net/str_ends_with)</li><li>[`get_resource_id`](https://php.net/get_resource_id)</li></ul> |
-->

### Downgrading code to PHP 7.1

Via [Rector](https://github.com/rectorphp/rector) (dry-run mode):

```bash
composer downgrade-code
```

## Standards

[PSR-1](https://www.php-fig.org/psr/psr-1), [PSR-4](https://www.php-fig.org/psr/psr-4) and [PSR-12](https://www.php-fig.org/psr/psr-12).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Static Analysis

Execute [phpstan](https://github.com/phpstan/phpstan) with level 8:

``` bash
composer analyse
```

To run checks for level 0 (or any level from 0 to 8):

``` bash
./vendor/bin/phpstan analyse -l 0 src tests
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/graphql-by-pop/graphql-server.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/graphql-by-pop/graphql-server/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/graphql-by-pop/graphql-server.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/graphql-by-pop/graphql-server.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/graphql-by-pop/graphql-server.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/graphql-by-pop/graphql-server
[link-travis]: https://travis-ci.org/graphql-by-pop/graphql-server
[link-scrutinizer]: https://scrutinizer-ci.com/g/graphql-by-pop/graphql-server/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/graphql-by-pop/graphql-server
[link-downloads]: https://packagist.org/packages/graphql-by-pop/graphql-server
[link-author]: https://github.com/leoloso
[link-contributors]: ../../contributors
