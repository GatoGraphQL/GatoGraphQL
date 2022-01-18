# PoP API

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Convert the application into a powerful API. Install the [GraphQL API](https://github.com/pop-api/api-graphql) package to convert it into a GraphQL server, and the [REST API](https://github.com/pop-api/api-rest) package to enable adding REST endpoints.

## Install

### Installing a fully-working API:

Follow the instructions under [Bootstrap a PoP API for WordPress](https://github.com/leoloso/PoP-API-WP) (even though CMS-agnostic, only the WordPress adapters have been presently implemented).

### Installing this library: 

Via Composer

``` bash
composer require pop-api/api
```

#### Enable pretty permalinks

##### Apache

Add the following code in the `.htaccess` file to enable API endpoint `/api/`:

```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /

# Rewrite from /some-url/api/ to /some-url/?scheme=api
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^(.*)/api/?$ /$1/?scheme=api [L,P,QSA]

# Rewrite from api/ to /?scheme=api
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^api/?$ /?scheme=api [L,P,QSA]
</IfModule>
```

To add pretty API endpoints for the extensions (GraphQL => `/api/graphql/`), REST => `/api/rest/`), add the following code to file `.htaccess`:

```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /

# a. Resource endpoints
# 1 and 2. GraphQL or REST: Rewrite from /some-url/api/(graphql|rest)/ to /some-url/?scheme=api&datastructure=(graphql|rest)
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^(.*)/api/(graphql|rest)/?$ /$1/?scheme=api&datastructure=$2 [L,P,QSA]

# b. Homepage single endpoint (root)
# 1 and 2. GraphQL or REST: Rewrite from api/(graphql|rest)/ to /?scheme=api&datastructure=(graphql|rest)
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^api/(graphql|rest)/?$ /?scheme=api&datastructure=$1 [L,P,QSA]
</IfModule>
```

##### Nginx

Add the following code in the Nginx configuration's `server` entry, to enable API endpoint `/api/`. Please notice that the resolver below is the one for Docker; replace this value for your environment.

```nginx
location ~ ^(.*)/api/?$ {
    # Resolver for Docker. Change to your own
    resolver 127.0.0.11 [::1];
    # If adding $args and it's empty, it does a redirect from /api/ to ?scheme=api.
    # Then, add $args only if not empty
    set $redirect_uri "$scheme://$server_name$1/?scheme=api";
    if ($args) {
        set $redirect_uri "$scheme://$server_name$1/?$args&scheme=api";
    }
    proxy_pass $redirect_uri;
}
```

To add pretty API endpoints for the extensions (GraphQL => `/api/graphql/`), REST => `/api/rest/`), add the following code:

```nginx
location ~ ^(.*)/api/(rest|graphql)/?$ {
    # Resolver for Docker. Change to your own
    resolver 127.0.0.11 [::1];
    set $redirect_uri "$scheme://$server_name$1/?scheme=api&datastructure=$2";
    if ($args) {
        set $redirect_uri "$scheme://$server_name$1/?$args&scheme=api&datastructure=$2";
    }
    proxy_pass $redirect_uri;
}
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`API/packages/api`](https://github.com/leoloso/PoP/tree/master/layers/API/packages/api).

## Usage

Initialize the component:

``` php
\PoP\Root\App::stockAndInitializeComponentClasses([([
    \PoPAPI\API\Component::class,
]);
```

> **Note:**<br/>To enable GraphQL and/or REST endpoints, the corresponding package must be installed: [GraphQL package](https://github.com/pop-api/api-graphql), [REST package](https://github.com/pop-api/api-rest) 

1. Transform any URL into an API endpoint by adding:

    `.../api/` (PoP native format)<br/>
    `.../api/graphql/` (GraphQL)<br/>
    `.../api/rest/` (REST)

2. Add your query under URL parameter `query`, following [this syntax](https://github.com/getpop/field-query)

<!-- ## Features

Please refer to the [features in the GraphQL package](https://github.com/pop-api/api-graphql#features). -->

<!--
### Query the root or URL-based resources

In the homepage, the initial selected resource on which the query is applied is `"root"`: 

- [/?query=posts.id|title|author.id|name](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|author.id|name)

Otherwise, the selected resource, or set of resources, is the corresponding one to the URL, such as a [single post](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/) or a [collection of posts](https://nextapi.getpop.org/posts/):

- [{single-post-url}/?query=id|title|author.id|name](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/graphql/?query=id|title|author.id|name)
- [{post-list-url}/?query=id|title|author.id|name](https://nextapi.getpop.org/posts/api/graphql/?query=id|title|author.id|name)

### Visualize the schema

To visualize all available fields, use query field `fullSchema` from the root: 

- [/?query=fullSchema](https://nextapi.getpop.org/api/graphql/?query=fullSchema)

### Query syntax

Please refer to the syntax from the [Field Query](https://github.com/getpop/field-query#syntax) package.

## All benefits from GraphQL and REST

The API can transform the application into both a GraphQL and/or REST server, simply by installing the corresponding extension:

- [GraphQL API](https://github.com/pop-api/api-graphql)
- [REST API](https://github.com/pop-api/api-rest)

The PoP API manages to provide all the same benefits of both REST and GraphQL APIs, **at the same time**:

_From GraphQL:_

- ✅ No over/under-fetching data
- ✅ Shape of the response mirrors query
- ✅ Field arguments (for filtering/pagination/formatting/etc)
- ✅ Directives (to change the behaviour of how data is fetched)
- ✅ Fetch all data using a single interface, from a single gateway

_From REST:_

- ✅ Server-side caching
- ✅ Secure: Not chance of Denial of Service attacks
- ✅ Can pre-define fields

## Additional features

The PoP API provides several features that neither REST or GraphQL support:

- ✅ URL-based queries ([example](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|date|content))
- ✅ Operators: `and`, `or`, `not`, `if`, `isNull`, `equals`, etc ([example](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|not(is-status(status:publish))))
- ✅ Helper functions ([example](https://nextapi.getpop.org/api/graphql/?query=context), [example](https://nextapi.getpop.org/api/graphql/?query=var(name:output)))
- ✅ Composable fields ([example](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|or([is-status(status:publish),is-status(status:draft)])))

<!--
### Examples

**REST:**

- [/?query=Retrieving default data (implicit fields)](https://nextapi.getpop.org/en/posts/api/?datastructure=rest)
- [/?query=Retrieving client-custom data (explicit fields)](https://nextapi.getpop.org/en/posts/api/?datastructure=rest&query=id|title|url|content,comments.id|content|date,comments.author.id|name|url,comments.author.posts.id|title|url)

**GraphQL:**

- [/?query=Retrieving client-custom data](https://nextapi.getpop.org/en/posts/api/?datastructure=graphql&query=id|title|url|content,comments.id|content|date,comments.author.id|name|url,comments.author.posts.id|title|url)
- [/?query=Returning an author's posts that contain a certain string](https://nextapi.getpop.org/author/themedemos/api/?datastructure=graphql&query=id|name,posts(searchfor:template).id|title|url)

**Note:** Setting parameter `datastructure` to either `graphql` or `rest` formats the response for the corresponding API. If `datastructure` is left empty, the response is the native one for PoP: a relational database structure (see "Data API layer" section below).
- - >

## Fast to resolve queries

PoP fetches a piece data from the database only once, even if the query fetches it several times. The query can include any number of nested relationships, and these are resolved with [complexity time](https://rob-bell.net/2009/06/a-beginners-guide-to-big-o-notation/) of `O(n^2)` in worst case, and `O(n)` in average case, where `n` is the number of nodes (both branches and leaves). 

(This is much better than for the <a href="https://blog.acolyer.org/2018/05/21/semantics-and-complexity-of-graphql/">typical GraphQL implementation</a>, which is `O(2^n)` in worst case, and `O(n^c)` to find out the query complexity.)

As a consequence, executing a query with multiple levels of nested properties will still be executed fairly quickly:

- [/?query=users.posts.author.posts.comments.id|content](https://nextapi.getpop.org/api/graphql/?query=users.posts.author.posts.comments.id|content)

## Decentralized schema

Taking advantage of the [component-based architecture](https://www.smashingmagazine.com/2019/01/introducing-component-based-api/) (as opposed to an architectured based on schemas, as the standard GraphQL implementation), the PoP API is natively decentralized. This has many benefits:

- Different versions of the API can be implemented for different projects or clients
- Different teams can work on the API at the same time, without affecting each others' work or have to plan together
- It becomes extremely easy to version control. For instance, each field can have its own versioning
- It becomes very easy to iterate. For instance, a quick fix for a bug can be deployed instantly, for the specific scenario under which the bug happens

For instance, we can develop a new feature for the API, such as adding a field argument `length` on the `excerpt` field, and initially release it under a branch called `"experimental"`. In order to use this field, the client is required to add this branch on the field arguments in the query:

_**Standard behaviour:**_<br/>
[/?query=posts.id|title|excerpt](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|excerpt)

_**New feature not yet available:**_<br/>
<a href="https://nextapi.getpop.org/api/graphql/?query=posts.id|title|excerpt(length:30)">/?query=posts.id|title|excerpt(length:30)</a>

_**New feature available under "experimental" branch:**_<br/>
<a href="https://nextapi.getpop.org/api/graphql/?query=posts.id|title|excerpt(branch:experimental,length:30)">/?query=posts.id|title|excerpt(length:30,branch:experimental)</a>

<!--
_**Overriding fields #2:**_

- Normal vs "Try new features" behaviour:<br/>[/?query=posts(limit:2).id|title|content|content(branch:try-new-features,project:block-metadata)](https://nextapi.getpop.org/api/graphql/?query=posts(limit:2).id|title|content|content(branch:try-new-features,project:block-metadata))
- - >

<!--
## Comparison among APIs

REST, GraphQL and PoP native compare like this:

<table>
<thead><th>&nbsp;</th><th>REST</th><th>GraphQL</th><th>PoP</th></thead>
<tr><th>Nature</th><td>Resource-based</td><td>Schema-based</td><td>Component-based</td></tr>
<tr><th>Endpoint</th><td>Custom endpoints based on resources</td><td>1 endpoint for the whole application</td><td>1 endpoint per page, simply adding parameter <code>output=json</code> to the page URL</td></tr>
<tr><th>Retrieved data</th><td>All data for a resource</td><td>All data for all resources in a component</td><td>All data for all resources in a component, for all components in a page</td></tr>
<tr><th>How are data fields retrieved?</th><td>Implicitly: already known on server-side</td><td>Explicitly: only known on client-side</td><td>Both Implicitly and Explicitly are supported (the developer decides)</td></tr>
<tr><th>Time complexity to fetch data</th><td>Constant (O(1))</td><td>At least <a href="https://blog.acolyer.org/2018/05/21/semantics-and-complexity-of-graphql/">Polynomial</a> (O(n^c))</td><td>Linear (O(n))</td></tr>
<tr><th>Can post data?</th><td>Yes</td><td>Yes</td><td>Yes</td></tr>
<tr><th>Can execute any type of other operation (eg: log in user, send an email, etc)?</th><td>No</td><td>No</td><td>Yes</td></tr>
<tr><th>Does it under/over-fetch data?</th><td>Yes</td><td>No</td><td>No</td></tr>
<tr><th>Is data normalized?</th><td>No</td><td>No</td><td>Yes</td></tr>
<tr><th>Support for configuration values?</th><td>No</td><td>No</td><td>Yes</td></tr>
<tr><th>Cacheable on server-side?</th><td>Yes</td><td>No</td><td>Yes</td></tr>
<tr><th>Open to DoS attack?</th><td>No</td><td><a href="https://blog.apollographql.com/securing-your-graphql-api-from-malicious-queries-16130a324a6b">Yes</a></td><td>No</td></tr>
<tr><th>Compatible with the other APIs</th><td>No</td><td>No</a></td><td>Yes</td></tr>
</table>
- - >

## Handling errors

The API returns error messages, which are categorized depending on their severity:

_**Deprecated fields:** (Severity: low)_

Fields that are not used anymore, and will eventually be replaced with another field (most likely in a future version of the API)

- [/?query=posts.id|title|published](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|published)

_**Schema warnings:** (Severity: medium)_

Errors in the schema on non-mandatory field arguments, which can be ignored and do not halt the execution of the query

- [/?query=posts(limit:3.5).id|title](https://nextapi.getpop.org/api/graphql/?query=posts(limit:3.5).id|title)

_**Database warnings:** (Severity: medium)_

Errors produced when data fetched from the queried object causes an error on its nesting field

- <a href="https://nextapi.getpop.org/api/graphql/?query=users.posts(limit:name()).id|title">/?query=users.posts(limit:name()).id|title</a>

_**Query errors:** (Severity: high)_

Whenever the query uses a wrong syntax, which prevents it from being parsed/interpreted properly

- <a href="https://nextapi.getpop.org/api/graphql/?query=posts.id[book](key:value))">/?query=posts.id[book](key:value)</a>

_**Schema errors:** (Severity: high)_

Whenever the query refers to non-existing fields, or using non-valid values

- [/?query=posts.id|title|non-existant-field|is-status(status:non-existant-value)](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|non-existant-field|is-status(status:non-existant-value))

_**Database errors:** (Severity: high)_

Errors produced when retrieving data from the database, that halt the execution of the query

### Error bubbling

Within composable fields, errors bubble up: Since the output from a field is the input to another one, if the output field fails, the input field may also fail:

- <a href="https://nextapi.getpop.org/api/graphql/?query=post(divide(a,4)).id|title">/?query=post(divide(a,4)).id|title</a>

## Examples

Examples below use the GraphQL API. More examples can be found on the [Field Query](https://github.com/getpop/field-query) package.

### Queries

_**Grouping properties:**_

- [/?query=posts.id|title|url](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|url)

_**Deep nesting:**_

- [/?query=posts.id|title|url|comments.id|content|date|author.id|name|url|posts.id|title|url](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|url|comments.id|content|date|author.id|name|url|posts.id|title|url)

_**Field arguments:**_

- [/?query=posts(searchfor:template,limit:3).id|title](https://nextapi.getpop.org/api/graphql/?query=posts(searchfor:template,limit:3).id|title)

_**Variables:**_

- [/?query=posts(searchfor:$search,limit:$limit).id|title&variables[limit]=3&variables[search]=template](https://nextapi.getpop.org/api/graphql/?query=posts(searchfor:$search,limit:$limit).id|title&variables[limit]=3&variables[search]=template)

_**Aliases:**_

- [/?query=posts(searchfor:template,limit:3)@searchposts.id|title](https://nextapi.getpop.org/api/graphql/?query=posts(searchfor:template,limit:3)@searchposts.id|title)

_**Bookmarks:**_

- [/?query=posts(searchfor:template,limit:3)[searchposts].id|title,[searchposts].author.id|name](https://nextapi.getpop.org/api/graphql/?query=posts(searchfor:template,limit:3)[searchposts].id|title,[searchposts].author.id|name)

_**Bookmark + Alias:**_

- [/?query=posts(searchfor:template,limit:3)[@searchposts].id|title,[searchposts].author.id|name](https://nextapi.getpop.org/api/graphql/?query=posts(searchfor:template,limit:3)[@searchposts].id|title,[searchposts].author.id|name)

_**Fragments:**_

- [/?query=posts.--fr1&fragments[fr1]=id|author.posts(limit:1).id|title](https://nextapi.getpop.org/api/graphql/?query=posts.--fr1&fragments[fr1]=id|author.posts(limit:1).id|title)

_**Directives:**_

- [/?query=posts.id|title|url<include(if:$include)>&variables[include]=true](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|url<include(if:$include)>&variables[include]=true)
- [/?query=posts.id|title|url<skip(if:$skip)>&variables[skip]=](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|url<skip(if:$skip)>&variables[skip]=)

_**Operators:**_

- <a href="https://nextapi.getpop.org/api/graphql/?query=or([1, 0])">/?query=or([1, 0])</a>
- <a href="https://nextapi.getpop.org/api/graphql/?query=and([1, 0])">/?query=and([1, 0])</a>

_**Composable fields:**_

- [/?query=posts.id|title|or([is-status(status:draft),is-status(status:published)])](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|or([is-status(status:draft),is-status(status:published)]))

_**Directives with composable fields:**_

- [/?query=posts.id|title|comments<include(if:hasComments())>.id|content](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|comments<include(if:hasComments())>.id|content)

_**Context:**_

- [/?query=context](https://nextapi.getpop.org/api/graphql/?query=context)

_**Context variable:**_

- [/?query=var(name:datastructure)](https://nextapi.getpop.org/api/graphql/?query=var(name:datastructure))

_**Operator over context variable:**_

- [/?query=equals(var(name:datastructure),graphql)|equals(var(name:datastructure),rest)](https://nextapi.getpop.org/api/graphql/?query=equals(var(name:datastructure),graphql)|equals(var(name:datastructure),rest))

<!--
## Architecture Design and Implementation

### Custom-Querying API

Similar to GraphQL, PoP also provides an API which can be queried from the client, which retrieves exactly the data fields which are requested and nothing more. The custom-querying API is accessed by appending `/api` to the URL and adding parameter `query` with the list of fields to retrieve from the queried resources. 

For instance, the following link fetches a collection of posts. By adding `query=title,content,datetime` we retrieve only these items:

- Original: https://nextapi.getpop.org/posts/?output=json
- Custom-querying: https://nextapi.getpop.org/posts/api/?query=id|title|content|datetime

The links above demonstrate fetching data only for the queried resources. What about their relationships? For instance, let’s say that we want to retrieve a list of posts with fields "title" and "content", each post’s comments with fields "content" and "date", and the author of each comment with fields "name" and "url". To achieve this in GraphQL we would implement the following query:

```graph
query {
  post {
    title
    content
    comments {
      content
      date
      author {
        name
        url
      }
    }
  }
}
```

PoP, instead, uses a query translated into its corresponding “dot syntax” expression, which can then be supplied through parameter query. Querying on a “post” resource, this value is:

```properties
query=title,content,comments.content,comments.date,comments.author.name,comments.author.url
```

Or it can be simplified, using | to group all fields applied to the same resource:

```properties
query=title|content,comments.content|date,comments.author.name|url
```

When executing this query on a [single post](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/?query=id|title|content,comments.content|date,comments.author.name|url) we obtain exactly the required data for all involved resources:

```javascript
{
  "datasetmodulesettings": {
    "dataload-relationalfields-singlepost": {
      "dbkeys": {
        "id": "posts",
        "comments": "comments",
        "comments.author": "users"
      }
    }
  },
  "datasetmoduledata": {
    "dataload-relationalfields-singlepost": {
      "dbobjectids": [
        23691
      ]
    }
  },
  "databases": {
    "posts": {
      "23691": {
        "id": 23691,
        "title": "A lovely tango",
        "content": "<div class=\"responsiveembed-container\"><iframe width=\"480\" height=\"270\" src=\"https:\\/\\/www.youtube.com\\/embed\\/sxm3Xyutc1s?feature=oembed\" frameborder=\"0\" allowfullscreen><\\/iframe><\\/div>\n",
        "comments": [
          "25094",
          "25164"
        ]
      }
    },
    "comments": {
      "25094": {
        "id": "25094",
        "content": "<p><a class=\"hashtagger-tag\" href=\"https:\\/\\/newapi.getpop.org\\/tags\\/videos\\/\">#videos<\\/a>\\u00a0<a class=\"hashtagger-tag\" href=\"https:\\/\\/newapi.getpop.org\\/tags\\/tango\\/\">#tango<\\/a><\\/p>\n",
        "date": "4 Aug 2016",
        "author": "851"
      },
      "25164": {
        "id": "25164",
        "content": "<p>fjlasdjf;dlsfjdfsj<\\/p>\n",
        "date": "19 Jun 2017",
        "author": "1924"
      }
    },
    "users": {
      "851": {
        "id": 851,
        "name": "Leonardo Losoviz",
        "url": "https:\\/\\/newapi.getpop.org\\/u\\/leo\\/"
      },
      "1924": {
        "id": 1924,
        "name": "leo2",
        "url": "https:\\/\\/newapi.getpop.org\\/u\\/leo2\\/"
      }
    }
  }
}
```

Hence, PoP can query resources in a REST fashion, and specify schema-based queries in a GraphQL fashion, and we will obtain exactly what is required, without over or underfetching data, and normalizing data in the database so that no data is duplicated. The query can include any number of nested relationships, and these are resolved with linear complexity time: worst case of O(n+m), where n is the number of nodes that switch domain (in this case 2: `comments` and `comments.author`) and m is the number of retrieved results (in this case 5: 1 post + 2 comments + 2 users), and average case of O(n).
- - >
-->

## PHP versions

Requirements:

- PHP 8.0+ for development
- PHP 7.1+ for production

### Supported PHP features

Check the list of [Supported PHP features in `leoloso/PoP`](https://github.com/leoloso/PoP/blob/master/docs/supported-php-features.md)

### Preview downgrade to PHP 7.1

Via [Rector](https://github.com/rectorphp/rector) (dry-run mode):

```bash
composer preview-code-downgrade
```

## Standards

[PSR-1](https://www.php-fig.org/psr/psr-1), [PSR-4](https://www.php-fig.org/psr/psr-4) and [PSR-12](https://www.php-fig.org/psr/psr-12).

To check the coding standards via [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer), run:

``` bash
composer check-style
```

To automatically fix issues, run:

``` bash
composer fix-style
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

To execute [PHPUnit](https://phpunit.de/), run:

``` bash
composer test
```

## Static Analysis

To execute [PHPStan](https://github.com/phpstan/phpstan), run:

``` bash
composer analyse
```

## Report issues

To report a bug or request a new feature please do it on the [PoP monorepo issue tracker](https://github.com/leoloso/PoP/issues).

## Contributing

We welcome contributions for this package on the [PoP monorepo](https://github.com/leoloso/PoP) (where the source code for this package is hosted).

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

GNU General Public License v2 (or later). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/pop-api/api.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/pop-api/api/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/pop-api/api.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/pop-api/api.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pop-api/api.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/pop-api/api
[link-travis]: https://travis-ci.org/pop-api/api
[link-scrutinizer]: https://scrutinizer-ci.com/g/pop-api/api/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/pop-api/api
[link-downloads]: https://packagist.org/packages/pop-api/api
[link-author]: https://github.com/leoloso
[link-contributors]: ../../../../../../contributors



<!--
> **Note:** The usage below belong to [PoP API for WordPress](https://github.com/leoloso/PoP-API-WP). Other configurations (eg: for other CMSs, to set-up a website instead of an API, and others) are coming soon.

For the **REST-compatible API**, add parameter `datastructure=rest` to the endpoint URL. 

For the **GraphQL-compatible API**, add parameter `datastructure=graphql` to the endpoint URL, and parameter `query` with the fields to retrieve (using a [custom dot notation](https://github.com/leoloso/PoP#defining-what-data-to-fetch-through-fields)) from the list of fields defined below. In addition, a field may have [arguments](https://github.com/leoloso/PoP#field-arguments) to modify its results.

For the **PoP native API**, add parameter `query` to the endpoint URL, similar to GraphQL.

----

Currently, the API supports the following entities and fields:

### Posts

**Endpoints**:

_List of posts:_

- **REST:** [/posts/api/?datastructure=rest](https://nextapi.getpop.org/posts/api/?datastructure=rest)
- **GraphQL:** [/posts/api/?datastructure=graphql](https://nextapi.getpop.org/posts/api/?datastructure=graphql&query=id|title|url)
- **PoP native:** [/posts/api/](https://nextapi.getpop.org/posts/api/?query=id|title|url)

_Single post:_

- **REST:** [/{SINGLE-POST-URL}/api/?datastructure=rest](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/?datastructure=rest) 
- **GraphQL:** [/{SINGLE-POST-URL}/api/?datastructure=graphql](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/?datastructure=graphql&query=id|title|date|content)
- **PoP native:** [/{SINGLE-POST-URL}/api/](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/?query=id|title|date|content)

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (arguments)</th><th>Relational (arguments)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>post-type<br/>published<br/>not-published<br/>title<br/>content<br/>url<br/>endpoint<br/>excerpt<br/>status<br/>is-draft<br/>date (format)<br/>datetime (format)<br/>comments-url<br/>commentCount<br/>hasComments<br/>published-with-comments<br/>cats<br/>cat<br/>cat-name<br/>cat-slugs<br/>tag-names<br/>hasThumb<br/>featuredImage<br/>featuredImage-props (size)</td><td>comments<br/>tags (limit, offset, order, searchfor)<br/>author</td></tr>
</tbody>
</table>

**Examples:**

_List of posts + author data:_<br/>[/?query=id|title|date|url,author.id|name|url,author.posts.id|title|url](https://nextapi.getpop.org/posts/api/?datastructure=graphql&query=id|title|date|url,author.id|name|url,author.posts.id|title|url)

_Single post + tags (ordered by slug), comments and comment author info:_<br/>[/?query=id|title|cat-slugs,tags(order:slug|asc).id|slug|count|url,comments.id|content|date,comments.author.id|name|url](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/?datastructure=graphql&query=id|title|cat-slugs,tags(order:slug|asc).id|slug|count|url,comments.id|content|date,comments.author.id|name|url)

### Users

**Endpoints:**

_List of users:_

- **REST:** [/users/api/?datastructure=rest](https://nextapi.getpop.org/users/api/?datastructure=rest)
- **GraphQL:** [/users/api/?datastructure=graphql](https://nextapi.getpop.org/users/api/?datastructure=graphql&query=id|name|url)
- **PoP native:** [/users/api/](https://nextapi.getpop.org/users/api/?query=id|name|url)

_Author:_

- **REST:** [/{AUTHOR-URL}/api/?datastructure=rest](https://nextapi.getpop.org/author/themedemos/api/?datastructure=rest) 
- **GraphQL:** [/{AUTHOR-URL}/api/?datastructure=graphql](https://nextapi.getpop.org/author/themedemos/api/?datastructure=graphql&query=id|name|description)
- **PoP native:** [/{AUTHOR-URL}/api/](https://nextapi.getpop.org/author/themedemos/api/?query=id|name|description)

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (arguments)</th><th>Relational (arguments)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>username<br/>user-nicename<br/>nicename<br/>name<br/>display-name<br/>firstName<br/>lastName<br/>email<br/>url<br/>endpoint<br/>description<br/>website-url</td><td>posts (limit, offset, order, searchfor, date-from, date-to)</td></tr>
</tbody>
</table>

**Examples:**

_List of users + up to 2 posts for each, ordered by date:_<br/>[/?query=id|name|url,posts(limit:2;order:date|desc).id|title|url|date](https://nextapi.getpop.org/users/api/?datastructure=graphql&query=id|name|url,posts(limit:2,order:date|desc).id|title|url|date)

_Author + all posts, with their tags and comments, and the comment author info:_<br/>[/?query=id|name|url,posts.id|title,posts.tags.id|slug|count|url,posts.comments.id|content|date,posts.comments.author.id|name](https://nextapi.getpop.org/author/themedemos/api/?datastructure=graphql&query=id|name|url,posts.id|title,posts.tags.id|slug|count|url,posts.comments.id|content|date,posts.comments.author.id|name)

### Comments

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (arguments)</th><th>Relational (arguments)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>content<br/>author-name<br/>author-url<br/>author-email<br/>approved<br/>type<br/>date (format)</td><td>author<br/>post<br/>post-id<br/>parent</td></tr>
</tbody>
</table>

**Examples:**

_Single post's comments:_<br/>[/?query=comments.id|content|date|type|approved|author-name|author-url|author-email](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/?datastructure=graphql&query=comments.id|content|date|type|approved|author-name|author-url|author-email)

### Tags

**Endpoints:**

_List of tags:_

- **REST:** [/tags/api/?datastructure=rest](https://nextapi.getpop.org/tags/api/?datastructure=rest)
- **GraphQL:** [/tags/api/?datastructure=graphql](https://nextapi.getpop.org/tags/api/?datastructure=graphql&query=id|slug|count|url)
- **PoP native:** [/tags/api/](https://nextapi.getpop.org/tags/api/?query=id|slug|count|url)

_Tag:_

- **REST:** [/{TAG-URL}/api/?datastructure=rest](https://nextapi.getpop.org/tag/html/api/?datastructure=rest) 
- **GraphQL:** [/{TAG-URL}/api/?datastructure=graphql](https://nextapi.getpop.org/tag/html/api/?datastructure=graphql&query=id|name|slug|count)
- **PoP native:** [/{TAG-URL}/api/](https://nextapi.getpop.org/tag/html/api/?query=id|name|slug|count)

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (arguments)</th><th>Relational (arguments)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>symbol<br/>symbolnamedescription<br/>namedescription<br/>url<br/>endpoint<br/>symbolname<br/>name<br/>slug<br/>term_group<br/>term_taxonomy_id<br/>taxonomy<br/>description<br/>count</td><td>parent<br/>posts (limit, offset, order, searchfor, date-from, date-to)</td></tr>
</tbody>
</table>

**Examples:**

_List of tags + all their posts filtered by date and ordered by title, their comments, and the comment authors:_<br/>[/?query=id|slug|count|url,posts(date-from:2009-09-15;date-to:2010-07-10;order:title|asc).id|title|url|date](https://nextapi.getpop.org/tags/api/?datastructure=graphql&query=id|slug|count|url,posts(date-from:2009-09-15,date-to:2010-07-10,order:title|asc).id|title|url|date)

_Tag + all their posts, their comments and the comment authors:_<br/>[/?query=id|slug|count|url,posts.id|title,posts.comments.content|date,posts.comments.author.id|name|url](https://nextapi.getpop.org/tag/html/api/?datastructure=graphql&query=id|slug|count|url,posts.id|title,posts.comments.content|date,posts.comments.author.id|name|url)

### Pages

**Endpoints:**

_Page:_

- **REST:** [/{PAGE-URL}/api/?datastructure=rest](https://nextapi.getpop.org/about/api/?datastructure=rest)
- **GraphQL:** [/{PAGE-URL}/api/?datastructure=graphql](https://nextapi.getpop.org/about/api/?datastructure=graphql&query=id|title|content)
- **PoP native:** [/{PAGE-URL}/api/](https://nextapi.getpop.org/about/api/?query=id|title|content)

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (arguments)</th><th>Relational (arguments)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>title<br/>content<br/>url</td><td>&nbsp;</td></tr>
</tbody>
</table>

**Examples:**

_Page:_<br/>[/?query=id|title|content|url](https://nextapi.getpop.org/about/api/?datastructure=graphql&query=id|title|content|url)
-->
