# PoP API

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Convert the application into a powerful API. Install the [GraphQL API](https://github.com/PoP-PoPAPI/api-graphql) package to convert it into a GraphQL server, and the [REST API](https://github.com/PoP-PoPAPI/api-rest) package to enable adding REST endpoints.

## Install

### Installing a fully-working API

Follow the instructions under [Bootstrap a PoP API for WordPress](https://github.com/leoloso/PoP-API-WP) (even though CMS-agnostic, only the WordPress adapters have been presently implemented).

### Installing this library

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
\PoP\Root\App::stockAndInitializeModuleClasses([([
    \PoPAPI\API\Module::class,
]);
```

> **Note:**<br/>To enable GraphQL and/or REST endpoints, the corresponding package must be installed: [GraphQL package](https://github.com/PoP-PoPAPI/api-graphql), [REST package](https://github.com/PoP-PoPAPI/api-rest)

<!-- 1. Transform any URL into an API endpoint by adding:

    `.../api/` (PoP native format)<br/>
    `.../api/graphql/` (GraphQL)<br/>
    `.../api/rest/` (REST)

2. Add your query under URL parameter `query`, following [this syntax](https://github.com/getpop/field-query) -->

<!-- ## Features

Please refer to the [features in the GraphQL package](https://github.com/PoP-PoPAPI/api-graphql#features). -->

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

- [GraphQL API](https://github.com/PoP-PoPAPI/api-graphql)
- [REST API](https://github.com/PoP-PoPAPI/api-rest)

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
  "datasetcomponentsettings": {
    "dataload-relationalfields-singlepost": {
      "outputKeys": {
        "id": "posts",
        "comments": "comments",
        "comments.author": "users"
      }
    }
  },
  "datasetcomponentdata": {
    "dataload-relationalfields-singlepost": {
      "objectIDs": [
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

<!-- It is influenced by GraphQL as an interface (for instance, it has types, fields and directives), but it has important differences:

- The fields with data can be defined in the [component model](../Engine/packages/component-model). This allows a component to define what data it needs, and the engine will resolve and provide this data already on the back-end (thus avoiding the round-trip from the client-side communicating with the API). -->
<!-- 
## Features

### Queries are URL-based

Structure of the request:

```less
/?query=query&variable=value&fragment=fragmentQuery
```

Structure of the query:

```less
/?query=field(args)@alias<directive(args)>
```

This syntax:

- Enables HTTP/Server-side caching
- Simplifies visualization/execution of queries (straight in the browser, without any client)
- GET when it's a GET, POST when it's a POST, pass variables through URL params

This syntax can be expressed in multiple lines:

```less
/?
query=
  field(
    args
  )@alias<
    directive(
      args
    )
  > 
```

Advantages:

- It is easy to read and write as a URL param (it doesn't make use of `{` and `}` like GraphQL)
- Copy/pasting in Firefox works straight!

Example:

```less
/?
query=
  posts(
    limit: 5
  )@posts.
    id|
    dateStr(format: d/m/Y)|
    title<
      skip(if: false)
    >
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts(pagination:{limit:5})@posts.id%7CdateStr(format:d/m/Y)%7Ctitle<skip(if:false)>">View query results</a>

The syntax has the following elements:

- `(key:value)` : Arguments
- `[key:value]` or `[value]` : Array
- `$` : Variable
- `@` : Alias
- `.` : Advance relationship
- `|` : Fetch multiple fields
- `<...>` : Directive
- `--` : Fragment

Example:

```less
/?
query=
  posts(
    filter: { ids: [1, 1499, 1178] },
    sort: { by: $order }
  )@posts.
    id|
    dateStr(format: d/m/Y)|
    title<
      skip(if: false)
    >|
    --props&
order=title|ASC&
props=
  url|
  author.
    name|
    url
```

<a href="https://newapi.getpop.org/api/graphql/?order=TITLE%7CASC&amp;props=url%7Cauthor.name%7Curl&amp;query=posts(filter:{ids:%5B1,1499,1178%5D},sort:{by:%24order})@posts.id%7CdateStr(format:d/m/Y)%7Ctitle<skip(if:false)>%7C--props">View query results</a>

### Dynamic schema

Because it is generated from code, different schemas can be created for different use cases, from a single source of truth. And the schema is natively decentralized or federated, enabling different teams to operate on their own source code.

To visualize it, in addition to the standard introspection field `__schema`, we can query field `fullSchema`:

```less
/?query=fullSchema
```

<a href="https://newapi.getpop.org/api/graphql/?query=fullSchema">View query results</a>

### Skip argument names

Field and directive argument names can be deduced from the schema.

This query...

```less
// Query 1
/?
postId=1&
query=
  post({id:$postId}).
    dateStr(d/m/Y)|
    title<
      skip(false)
    >
```

...is equivalent to this query:

```less
// Query 2
/?
postId=1&
query=
  post(by:{id:$postId}).
    dateStr(format:d/m/Y)|
    title<
      skip(if:false)
    >
```

<a href="https://newapi.getpop.org/api/graphql/?postId=1&amp;query=post({id:%24postId}).dateStr(d/m/Y)%7Ctitle%3Cskip(false)%3E">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?postId=1&amp;query=post(by:{id:%24postId}).dateStr(format:d/m/Y)%7Ctitle<skip(if:false)>">View query results #2</a>

### Operators and Helpers

All operators and functions provided by the language (PHP) can be made available as standard fields, and any custom “helper” functionality can be easily implemented too:

```less
1. /?query=not(true)
2. /?query=or([1,0])
3. /?query=and([1,0])
4. /?query=if(true, Show this text, Hide this text)
5. /?query=equals(first text, second text)
6. /?query=isNull(),isNull(something)
7. /?query=sprintf(%s API is %s, [PoP, cool])
8. /?query=context
```

<a href="https://newapi.getpop.org/api/graphql?query=not(true)">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql?query=or(%5B1,0%5D)">View query results #2</a>

<a href="https://newapi.getpop.org/api/graphql?query=and(%5B1,0%5D)">View query results #3</a>

<a href="https://newapi.getpop.org/api/graphql?query=if(true,Show%20this%20text,Hide%20this%20text)">View query results #4</a>

<a href="https://newapi.getpop.org/api/graphql?query=equals(first%20text,%20second%20text)">View query results #5</a>

<a href="https://newapi.getpop.org/api/graphql?query=isNull(),isNull(something)">View query results #6</a>

<a href="https://newapi.getpop.org/api/graphql?query=sprintf(%s%20API%20is%20%s,%20%5BPoP,%20cool%5D)">View query results #7</a>

<a href="https://newapi.getpop.org/api/graphql?query=context">View query results #8</a>

### Composable fields

The value from a field can be the input to another field, and there is no limit how many levels deep it can be.

In the example below, field `post` is injected, in its field argument `id`, the value from field `arrayItem` applied to field `posts`:

```less
/?query=
  post(
    by: {
      id: arrayItem(
        posts(
          pagination: { limit: 1 },
          sort: {by: DATE, order: DESC}
        ),
        0
      )
    }
  )@latestPost.
    id|
    title|
    date
```

<a href="https://newapi.getpop.org/api/graphql/?query=post(by:{id:arrayItem(posts(pagination:{limit:1},sort:{by:date,order:DESC}),0)})@latestPost.id%7Ctitle%7Cdate">View query results</a>

To tell if a field argument must be considered a field or a string, if it contains `()` it is a field, otherwise it is a string (eg: `posts()` is a field, and `posts` is a string)

### Composable fields with operators and helpers

Operators and helpers are standard fields, so they can be employed for composable fields. This makes available composable elements to the query, which removes the need to implement custom code in the resolvers, or to fetch raw data that is then processed in the application in the client-side. Instead, logic can be provided in the query itself.

```less
/?
format=Y-m-d&
query=
  posts.
    if (
      hasComments(), 
      sprintf(
        "This post has %s comment(s) and title '%s'", [
          commentCount(),
          title()
        ]
      ), 
      sprintf(
        "This post was created on %s and has no comments", [
          dateStr(format: if(not(empty($format)), $format, d/m/Y))
        ]
      )
    )@postDesc
```

<a href="https://newapi.getpop.org/api/graphql/?format=Y-m-d&amp;query=posts.if(hasComments(),sprintf(%22This%20post%20has%20%s%20comment(s)%20and%20title%20%27%s%27%22,%5BcommentCount(),title()%5D),sprintf(%22This%20post%20was%20created%20on%20%s%20and%20has%20no%20comments%22,%5BdateStr(format:if(not(empty(%24format)),%24format,d/m/Y))%5D))@postDesc">View query results</a>

This solves an issue with GraphQL: That we may need to define a field argument with arbitrary values in order to provide variations of the field's response (which is akin to REST's way of creating multiple endpoints to satisfy different needs, such as `/posts-1st-format/` and `/posts-2nd-format/`).

### Composable fields in directive arguments

Through composable fields, the directive can be evaluated against the object, granting it a dynamic behavior.

The example below implements the standard GraphQL `skip` directive, however it is able to decide if to skip the field or not based on a condition from the object itself:

```less
/?query=
  posts.
    title|
    featuredImage<
      skip(if:isNull(featuredImage()))
    >.
      src
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts.title%7CfeaturedImage<skip(if:isNull(featuredImage()))>.src">View query results</a>

This behaviour enables to modify the output of the field on a fine-grained manner. For instance, the following code satisfies the same example from the section above, using directive `include` to alternate the output of a field between several values, based on the post object having comments or not:

```less
/?
format=Y-m-d&
query=
  posts.
    sprintf(
      "This post has %s comment(s) and title '%s'", [
        commentCount(),
        title()
      ]
    )@postDesc<include(if:hasComments())>|
    sprintf(
      "This post was created on %s and has no comments", [
        dateStr(format: if(not(empty($format)), $format, d/m/Y))
      ]
    )@postDesc<include(if:not(hasComments()))>
```

<a href="https://newapi.getpop.org/api/graphql/?format=Y-m-d&query=posts.sprintf(%22This%20post%20has%20%s%20comment(s)%20and%20title%20%27%s%27%22,%20[commentCount(),title()])@postDesc%3Cinclude(if:hasComments())%3E|sprintf(%22This%20post%20was%20created%20on%20%s%20and%20has%20no%20comments%22,%20[dateStr(format:%20if(not(empty($format)),%20$format,%20d/m/Y))])@postDesc%3Cinclude(if:not(hasComments()))%3E">View query results</a>

### Skip output if null

Exactly the same result from section above (`<skip(if(isNull(...)))>`) can be accomplished using the `?` operator: Adding it after a field, it skips the output of its value if it is null.

```less
/?query=
  posts.
    title|
    featuredImage?.
      src
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts.title%7CfeaturedImage?.src">View query results</a>

### Composable directives

Directives can be nested, unlimited levels deep, enabling to create complex logic such as iterating over array elements and applying a function on them, changing the context under which a directive must execute, and others.

In the example below, directive `<forEach>` iterates all the elements from an array, and passes each of them to directive `<applyFunctionField>` which executes field `arrayJoin` on them:

```less
/?query=
  echo([
    [banana, apple],
    [strawberry, grape, melon]
  ])@fruitJoin<
    forEach<
      applyFunctionField(
        function: arrayJoin,
        addArguments: [
          array: %{value}%,
          separator: "---"
        ]
      )
    >
  >
```

<a href="https://newapi.getpop.org/api/graphql/?query=echo(%5B%5Bbanana,apple%5D,%5Bstrawberry,grape,melon%5D%5D)@fruitJoin%3CforEach%3CapplyFunctionField(function:arrayJoin,addArguments:%5Barray:%{value}%,separator:%22---%22%5D)%3E%3E">View query results</a>

### Directive expressions

An expression, defined through symbols `%...%`, is a variable used by directives to pass values to each other. An expression can be pre-defined by the directive or created on-the-fly in the query itself.

In the example below, an array contains strings to translate and the language to translate the string to. The array element is passed from directive `<forEach>` to directive `<underJSONObjectProperty>` through pre-defined expression `%{value}%`, and the language code is passed from directive `<underJSONObjectProperty>` to directive `<translate>` through variable `%{toLang}%`, which is defined only in the query:

```less
/?query=
  echo([
    {
      text: Hello my friends,
      translateTo: fr
    },
    {
      text: How do you like this software so far?,
      translateTo: es
    }
  ])@translated<
    forEach<
      underJSONObjectProperty(
        path: text,
        appendExpressions: {
          toLang:extract(%{value}%,translateTo)
        }
      )<
        translateMultiple(
          from: en,
          to: %{toLang}%,
          oneLanguagePerField: true
        )
      >
    >
  >
```

<a href="https://newapi.getpop.org/api/graphql/?query=echo(%5B{text:%20Hello%20my%20friends,translateTo:%20fr},{text:%20How%20do%20you%20like%20this%20software%20so%20far?,translateTo:%20es}%5D)@translated%3CforEach%3CunderJSONObjectProperty(path:%20text,appendExpressions:%20{toLang:extract(%{value}%,translateTo)})%3CtranslateMultiple(from:%20en,to:%20%{toLang}%,oneLanguagePerField:%20true)%3E%3E%3E">View query results</a>

### HTTP Caching

Cache the response from the query using standard [HTTP caching](https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching).

The response will contain `Cache-Control` header with the `max-age` value set at the time (in seconds) to cache the request, or `no-store` if the request must not be cached. Each field in the schema can configure its own `max-age` value, and the response's `max-age` is calculated as the lowest `max-age` among all requested fields (including composable fields).

Examples:

```less
//1. Operators have max-age 1 year
/?query=
  echo(Hello world!)

//2. Most fields have max-age 1 hour
/?query=
  echo(Hello world!)|
  posts.
    title

//3. Composable fields also supported
/?query=
  echo(posts())

//4. "time" field has max-age 0
/?query=
  time

//5. To not cache a response:
//a. Add field "time"
/?query=
  time|
  echo(Hello world!)|
  posts.
    title

//b. Add <cacheControl(maxAge:0)>
/?query=
  echo(Hello world!)|
  posts.
    title<cacheControl(maxAge:0)>
```

<a href="https://newapi.getpop.org/api/graphql/?query=echo(Hello+world!)">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?query=echo(Hello+world!)%7Cposts.title">View query results #2</a>

<a href="https://newapi.getpop.org/api/graphql/?query=echo(posts())">View query results #3</a>

<a href="https://newapi.getpop.org/api/graphql/?query=time">View query results #4</a>

<a href="https://newapi.getpop.org/api/graphql/?query=time%7Cecho(Hello+world!)%7Cposts.title">View query results #5</a>

<a href="https://newapi.getpop.org/api/graphql/?query=echo(Hello+world!)%7Cposts.title<cacheControl(maxAge:0)>">View query results #6</a>

### Many resolvers per field

Fields can be satisfied by many resolvers.

In the example below, field `excerpt` does not normally support field arg `length`, however a new resolver adds support for this field arg, and it is enabled by passing field arg `branch:experimental`:

```less
//1. Standard behaviour
/?query=
  posts.
    excerpt

//2. New feature not yet available
/?query=
  posts.
    excerpt(length:30)

//3. New feature available under
// experimental branch
/?query=
  posts.
    excerpt(
      length:30,
      branch:experimental
    )
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts.excerpt">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?query=posts.excerpt(length:30)">View query results #2</a>

<a href="https://newapi.getpop.org/api/graphql/?query=posts.excerpt(length:30,branch:experimental)">View query results #3</a>

Advantages:

- The data model can be customized for client/project
- Teams become autonoumous, and can avoid the bureaucracy of communicating/planning/coordinating changes to the schema
- Rapid iteration, such as allowing a selected group of testers to try out new features in production
- Quick bug fixing, such as fixing a bug specifically for a client, without worrying about breaking changes for other clients
- Field-based versioning

### Validate user state/roles

Fields can be made available only if user is logged-in, or has a specific role. When the validation fails, the schema can be set, by configuration, to either show an error message or hide the field, as to behave in public or private mode, depending on the user.

For instance, the following query will give an error message, since you, dear reader, are not logged-in:

```less
/?query=
  me.
    name
```

<a href="https://newapi.getpop.org/api/graphql/?query=me.name">View query results</a>

### Linear time complexity to resolve queries (`O(n)`, where `n` is #types)

The “N+1 problem” is completely avoided already by architectural design. It doesn't matter how many levels deep the graph is, it will resolve fast.

Example of a deeply-nested query:

```less
/?query=
  posts.
     author.
       posts.
         comments.
           author.
             id|
             name|
             posts.
               id|
               title|
               url|
               tags.
                 id|
                 slug
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts.author.posts.comments.author.id%7Cname%7Cposts.id%7Ctitle%7Curl%7Ctags.id%7Cslug">View query results</a>

### Efficient directive calls

Directives receive all their affected objects and fields together, for a single execution.

In the examples below, the Google Translate API is called the minimum possible amount of times to execute multiple translations:

```less
// The Google Translate API is called once,
// containing 10 pieces of text to translate:
// 2 fields (title and excerpt) for 5 posts
/?query=
  posts(pagination:{ limit: 5 }).
    --props|
    --props@spanish<
      translate(from:en,to:es)
    >&
props=
  title|
  excerpt

// Here there are 3 calls to the API, one for
// every language (Spanish, French and German),
// 10 strings each, all calls are concurrent
/?query=
  posts(pagination: { limit: 5 }).
    --props|
    --props@spanish<
      translate(from:en,to:es)
    >|
    --props@french<
      translate(from:en,to:fr)
    >|
    --props@german<
      translate(from:en,to:de)
    >&
props=
  title|
  excerpt
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts(pagination:{limit:5}).--props%7C--props@spanish<translate(from:en,to:es)>&amp;props=title%7Cexcerpt">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?query=posts(pagination:{limit:5}).--props%7C--props@spanish%3Ctranslate(from:en,to:es)%3E%7C--props@french%3Ctranslate(from:en,to:fr)%3E%7C--props@german%3Ctranslate(from:en,to:de)%3E&amp;props=title%7Cexcerpt">View query results #2</a>

### Interact with APIs from the back-end

Example calling the Google Translate API from the back-end, as coded within directive `<translate>`:

```less
//1. <translate> calls the Google Translate API
/?query=
  posts(pagination: { limit: 5 }).
    title|
    title@spanish<
      translate(from:en,to:es)
    >
    
//2. Translate to Spanish and back to English
/?query=
  posts(pagination: { limit: 5 }).
    title|
    title@translateAndBack<
      translate(from:en,to:es),
      translate(es,en)
    >
    
//3. Change the provider through arguments
// (link gives error: Azure is not implemented)
/?query=
  posts(pagination: { limit: 5 }).
    title|
    title@spanish<
      translate(from:en,to:es,provider:azure)
    >
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts(pagination:{limit:5}).title%7Ctitle@spanish%3Ctranslate(from:en,to:es)%3E">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?query=posts(pagination:{limit:5}).title%7Ctitle@translateAndBack%3Ctranslate(from:en,to:es),translate(es,en)%3E">View query results #2</a>

<a href="https://newapi.getpop.org/api/graphql/?query=posts(pagination:{limit:5}).title%7Ctitle@spanish%3Ctranslate(from:en,to:es,provider:azure)%3E">View query results #3</a>

### Interact with APIs from the client-side

Example accessing an external API from the query itself:

```less
/?query=
echo([
  usd: [
    bitcoin: extract(
      getJSON("https://api.cryptonator.com/api/ticker/btc-usd"), 
      ticker.price
    ),
    ethereum: extract(
      getJSON("https://api.cryptonator.com/api/ticker/eth-usd"), 
      ticker.price
    )
  ],
  euro: [
    bitcoin: extract(
      getJSON("https://api.cryptonator.com/api/ticker/btc-eur"), 
      ticker.price
    ),
    ethereum: extract(
      getJSON("https://api.cryptonator.com/api/ticker/eth-eur"), 
      ticker.price
    )
  ]
])@cryptoPrices
```

<a href="https://newapi.getpop.org/api/graphql/?query=echo(%5Busd:%5Bbitcoin:extract(getJSON(%22https://api.cryptonator.com/api/ticker/btc-usd%22),ticker.price),ethereum:extract(getJSON(%22https://api.cryptonator.com/api/ticker/eth-usd%22),ticker.price)%5D,euro:%5Bbitcoin:extract(getJSON(%22https://api.cryptonator.com/api/ticker/btc-eur%22),ticker.price),ethereum:extract(getJSON(%22https://api.cryptonator.com/api/ticker/eth-eur%22),ticker.price)%5D%5D)@cryptoPrices">View query results</a>

### Interact with APIs, performing all required logic in a single query

The last query from the examples below accesses, extracts and manipulates data from an external API, and then uses this result to accesse yet another external API:

```less
//1. Get data from a REST endpoint
/?query=
  getJSON("https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions")@userEmailLangList
    
//2. Access and manipulate the data
/?query=
  extract(
    getJSON("https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions"),
    email
  )@userEmailList
  
//3. Convert the data into an input to another system
/?query=
  getJSON(
    sprintf(
      "https://newapi.getpop.org/users/api/rest/?query=name|email%26emails[]=%s",
      [arrayJoin(
        extract(
          getJSON("https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions"),
          email
        ),
        "%26emails[]="
      )]
    )
  )@userNameEmailList
```

<a href="https://newapi.getpop.org/api/graphql/?query=getJSON(%22https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions%22)@userEmailLangList">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?query=extract(getJSON(%22https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions%22),email)@userEmailList">View query results #2</a>

<a href="https://newapi.getpop.org/api/graphql/?query=getJSON(sprintf(%22https://newapi.getpop.org/users/api/rest/?query=name%7Cemail%26emails%5B%5D=%s%22,%5BarrayJoin(extract(getJSON(%22https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions%22),email),%22%26emails%5B%5D=%22)%5D))@userEmailNameList">View query results #3</a>

### Create your content or service mesh

The example below defines and accesses a list of all services required by the application:

```less
/?query=
  echo([
    github: "https://api.github.com/repos/leoloso/PoP",
    weather: "https://api.weather.gov/zones/forecast/MOZ028/forecast",
    photos: "https://picsum.photos/v2/list"
  ])@meshServices|
  getAsyncJSON(getSelfProp(%{self}%, meshServices))@meshServiceData|
  echo([
    weatherForecast: extract(
      getSelfProp(%{self}%, meshServiceData),
      weather.properties.periods
    ),
    photoGalleryURLs: extract(
      getSelfProp(%{self}%, meshServiceData),
      photos.url
    ),
    githubMeta: echo([
      description: extract(
        getSelfProp(%{self}%, meshServiceData),
        github.description
      ),
      starCount: extract(
        getSelfProp(%{self}%, meshServiceData),
        github.stargazers_count
      )
    ])
  ])@contentMesh
```

<a href="https://newapi.getpop.org/api/graphql/?query=echo(%5Bgithub:%22https://api.github.com/repos/leoloso/PoP%22,weather:%22https://api.weather.gov/zones/forecast/MOZ028/forecast%22,photos:%22https://picsum.photos/v2/list%22%5D)@meshServices%7CgetAsyncJSON(getSelfProp(%{self}%,meshServices))@meshServiceData%7Cecho(%5BweatherForecast:extract(getSelfProp(%{self}%,meshServiceData),weather.properties.periods),photoGalleryURLs:extract(getSelfProp(%{self}%,meshServiceData),photos.url),githubMeta:echo(%5Bdescription:extract(getSelfProp(%{self}%,meshServiceData),github.description),starCount:extract(getSelfProp(%{self}%,meshServiceData),github.stargazers_count)%5D)%5D)@contentMesh">View query results</a>

### One-graph ready

Use custom fields to expose your data and create a single, comprehensive, unified graph.

The example below implements the same logic as the case above, however coding the logic through fields (instead of through the query):

```less
// 1. Inspect services
/?query=
  meshServices

// 2. Retrieve data
/?query=
  meshServiceData

// 3. Process data
/?query=
  contentMesh

// 4. Customize data
/?query=
  contentMesh(
    githubRepo: "pop-api/api-graphql",
    weatherZone: AKZ017,
    photoPage: 3
  )@contentMesh
```

<a href="https://newapi.getpop.org/api/graphql/?query=meshServices">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?query=meshServiceData">View query results #2</a>

<a href="https://newapi.getpop.org/api/graphql/?query=contentMesh">View query results #3</a>

<a href="https://newapi.getpop.org/api/graphql/?query=contentMesh(githubRepo:%22pop-api/api-graphql%22,weatherZone:AKZ017,photoPage:3)@contentMesh">View query results #4</a>

### Persisted fragments

Query sections of any size and shape can be stored in the server. It is like the persisted queries mechanism provided by GraphQL, but more granular: different persisted fragments can be added to the query, or a single fragment can itself be the query.

The example below demonstrates, once again, the same logic from the example above, but coded and stored as persisted fields:

```less
// 1. Save services
/?query=
  --meshServices

// 2. Retrieve data
/?query=
  --meshServiceData

// 3. Process data
/?query=
  --contentMesh

// 4. Customize data
/?
githubRepo=pop-api/api-graphql&
weatherZone=AKZ017&
photoPage=3&
query=
  --contentMesh
```

<a href="https://newapi.getpop.org/api/graphql/?query=--meshServices">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?query=--meshServiceData">View query results #2</a>

<a href="https://newapi.getpop.org/api/graphql/?query=--contentMesh">View query results #3</a>

<a href="https://newapi.getpop.org/api/graphql/?githubRepo=pop-api/api-graphql&amp;weatherZone=AKZ017&amp;photoPage=3&amp;query=--contentMesh">View query results #4</a>

### Persisted queries

Queries can also be persisted in the server, then we can just publish queries and disable access to the GraphQL server, increasing the security. 

In the `query` field, instead of passing the query, we pass a persisted query name, preceded with `!`:

```less
// 1. Access persisted query
/?query=
  !contentMesh

// 2. Customize it with variables
/?
githubRepo=pop-api/api-graphql&
weatherZone=AKZ017&
photoPage=3&
query=
  !contentMesh
```

<a href="https://newapi.getpop.org/api/graphql/?query=!contentMesh">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?githubRepo=pop-api/api-graphql&amp;weatherZone=AKZ017&amp;photoPage=3&amp;query=!contentMesh">View query results #2</a>

### Automatic namespacing of types

Namespaces help manage the complexity of the schema. This can avoid different types having the same name, which can happen when embedding components from a 3rd party.

PoP allows to have all types in the schema be automatically namespaced, by prepending their names with the corresponding PHP package's owner and name (following the [PSR-4](https://www.php-fig.org/psr/psr-4/) convention, PHP namespaces have the form of `ownerName\projectName`, such as `"PoP\ComponentModel"`). Namespacing is disabled by default, and enabled through an environment variable. More info [here](https://leoloso.com/posts/added-namespaces-to-graphql-by-pop/).

### Field/directive-based versioning

Fields and directives can be independently versioned, and the version to use can be specified in the query through the field/directive argument `versionConstraint`. 

To select the version for the field/directive, we use the same [semver version constraints employed by Composer](https://getcomposer.org/doc/articles/versions.md#writing-version-constraints):

```less
// Selecting version for fields
/?query=
  userServiceURLs(versionConstraint:^0.1)|
  userServiceURLs(versionConstraint:">0.1")|
  userServiceURLs(versionConstraint:^0.2)

// Selecting version for directives
/?query=
  post({id:$postId}).
    title@titleCase<makeTitle(versionConstraint:^0.1)>|
    title@upperCase<makeTitle(versionConstraint:^0.2)>
&postId=1
```

<a href="https://newapi.getpop.org/api/graphql/?query=userServiceURLs(versionConstraint:^0.1)|userServiceURLs(versionConstraint:%22%3E0.1%22)|userServiceURLs(versionConstraint:^0.2)">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?query=post({id:$postId}).title@titleCase%3CmakeTitle(versionConstraint:^0.1)%3E|title@upperCase%3CmakeTitle(versionConstraint:^0.2)%3E&postId=1">View query results #2</a>

### Combine GraphQL with REST

Get the best from both GraphQL and REST: query resources based on endpoint, with no under/overfetching.

```less
// Query data for a single resource
{single-post-url}/api/rest/?query=
  id|
  title|
  author.
    id|
    name

// Query data for a set of resources
{post-list-url}/api/rest/?query=
  id|
  title|
  author.
    id|
    name
```

<a href="https://newapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/rest/?query=id%7Ctitle%7Cauthor.id%7Cname">View query results #1</a>

<a href="https://newapi.getpop.org/posts/api/rest/?query=id%7Ctitle%7Cauthor.id%7Cname">View query results #2</a>

### Output in many formats

Replace `"/graphql"` from the URL to output the data in a different format: XML or as properties, or any custom one (implementation takes very few lines of code).

```less
// Output as XML: Replace /graphql with /xml
/api/xml/?query=
  posts.
    id|
    title|
    author.
      id|
      name

// Output as props: Replace /graphql with /props
/api/props/?query=
  posts.
    id|
    title|
    excerpt
```

<a href="https://newapi.getpop.org/api/xml/?query=posts.id%7Ctitle%7Cauthor.id%7Cname">View query results #1</a>

<a href="https://newapi.getpop.org/api/props/?query=posts.id%7Ctitle%7Cexcerpt">View query results #2</a>

### Normalize data for client

Just by removing the `"/graphql"` bit from the URL, the response is normalized, making its output size greatly reduced when a same field is fetched multiple times.

```less
/api/?query=
  posts.
     author.
       posts.
         comments.
           author.
             id|
             name|
             posts.
               id|
               title|
               url
```

Compare the output of the query in PoP native format:

<a href="https://newapi.getpop.org/api/?query=posts.author.posts.comments.author.id%7Cname%7Cposts.id%7Ctitle%7Curl">View query results</a>

...with the same output in GraphQL format:

<a href="https://newapi.getpop.org/api/graphql/?query=posts.author.posts.comments.author.id%7Cname%7Cposts.id%7Ctitle%7Curl">View query results</a>

### Handle issues by severity

Issues are handled differently depending on their severity:

- Informative, such as Deprecated fields and directives: to indicate they must be replaced with a substitute
- Non-blocking issues, such as Schema/Database warnings: when an issue happens on a non-mandatory field
- Blocking issues, such as Query/Schema/Database errors: when they use a wrong syntax, declare non-existing fields or directives, or produce an issues on mandatory arguments

```less
//1. Deprecated fields
/?query=
  posts.
    title|
    isPublished

//2. Schema warning
/?query=
  posts(pagination: { limit:3.5 }).
    title

//3. Database warning
/?query=
  users.
    posts(pagination: { limit:name() }).
      title

//4. Query error
/?query=
  posts.
    id[book](key:value)

//5. Schema error
/?query=
  posts.
    nonExistantField|
    isStatus(
      status:non-existant-value
    )
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts.title%7CisPublished">View query results #1</a>

<a href="https://newapi.getpop.org/api/graphql/?query=posts(pagination:{limit:3.5}).title">View query results #2</a>

<a href="https://newapi.getpop.org/api/graphql/?query=users.posts(pagination:{limit:name()}).title">View query results #3</a>

<a href="https://newapi.getpop.org/api/graphql/?query=posts.id%5Bbook%5D(key:value)">View query results #4</a>

<a href="https://newapi.getpop.org/api/graphql/?query=posts.nonExistantField%7CisStatus(status:non-existant-value)">View query results #5</a>

### Type casting/validation

When an argument has its type declared in the schema, its inputs will be casted to the type. If the input and the type are incompatible, it ignores setting the input and throws a warning.

```less
/?query=
  posts(pagination:{limit:3.5}).
    title
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts(pagination:{limit:3.5}).title">View query results</a>

### Issues bubble upwards

If a field or directive fails and it is input to another field, this one may also fail.

```less
/?query=
  post(by: { id: divide(a,4) }).
    title
```

<a href="https://newapi.getpop.org/api/graphql/?query=post(by:{id:divide(a,4)}).title">View query results</a>

### Path to the issue

Issues contain the path to the composed field or directive were it was produced.

```less
/?query=
  echo([hola,chau])<
    forEach<
      translate(notexisting:prop)
    >
  >
```

<a href="https://newapi.getpop.org/api/graphql/?query=echo(%5Bhola,chau%5D)%3CforEach%3Ctranslate(notexisting:prop)%3E%3E">View query results</a>

### Log information

Any informative piece of information can be logged (enabled/disabled through configuration).

```less
/?
actions[]=show-logs&
postId=1&
query=
  post({id:$postId}).
    title|
    dateStr(d/m/Y)
```

<a href="https://newapi.getpop.org/api/graphql/?actions%5B%5D=show-logs&amp;postId=1&amp;query=post({id:%24postId}).title%7CdateStr(d/m/Y)">View query results</a>

### Embeddable fields

Syntactic sugar for composable fields: Resolve a field within an argument for another field from the same type, using syntax `{{ fieldName }}`, and also including arguments, using `{{ fieldName(fieldArgs) }}`.

```less
/?
query=
  posts.
    echo(({{ commentCount }}) {{ title }} - posted on {{ dateStr(d/m/Y) }})@title<include({{ hasComments }})>|
    title<skip({{ hasComments }})>
```

<a href="https://newapi.getpop.org/api/graphql/?query=posts.echo(({{%20commentCount%20}})%20{{%20title%20}}%20-%20posted%20on%20{{%20dateStr(d/m/Y)%20}})@title%3Cinclude({{%20hasComments%20}})%3E|title%3Cskip({{%20hasComments%20}})%3E">View query results</a>

### Mutations

The query is able to place mutations anywhere (not only on the root) and these are integrated to the graph: The mutation result can, itself, be input to another field, be added to a nested subquery, and so on.

```less
/?query=
  addPost($title, $content).
    addComment($comment1)|
    addComment($comment2).
      author<sendConfirmationByEmail>.
        followers<notifyByEmail, notifyBySlack>
```

## Example using the API

**Use case to implement:**

Create an automated email-sending service using data from 3 sources:

1. A REST API to fetch the recipients (list of rows with columns `email` and `lang`)
2. A REST API to fetch client data (a list of rows with columns `email` and `name`)
3. Blog posts published in your website

The email sent to the recipient must be customized:

1. Greeting the person by name
2. Translating the blog post's content to the user's preferred language

**Solution:**

```less
/?
postId=1&
query=
  post({id:$postId})@post.
    content|
    dateStr(d/m/Y)@date,
  getJSON("https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions")@userList|
  arrayUnique(
    extract(
      getSelfProp(%{self}%, userList),
      lang
    )
  )@userLangs|
  extract(
    getSelfProp(%{self}%, userList),
    email
  )@userEmails|
  arrayOrObjectFill(
    getJSON(
      sprintf(
        "https://newapi.getpop.org/users/api/rest/?query=name|email%26emails[]=%s",
        [arrayJoin(
          getSelfProp(%{self}%, userEmails),
          "%26emails[]="
        )]
      )
    ),
    getSelfProp(%{self}%, userList),
    email
  )@userData;

  post({id:$postId})@post<
    copyRelationalResults(
      [content, date],
      [postContent, postDate]
    )
  >;

  getSelfProp(%{self}%, postContent)@postContent<
    translateMultiple(
      from: en,
      to: arrayDiff([
        getSelfProp(%{self}%, userLangs),
        [en]
      ])
    ),
    renameProperty(postContent-en)
  >|
  getSelfProp(%{self}%, userData)@userPostData<
    forEach<
      applyFunctionField(
        function: arrayAddItem(
          array: [],
          value: ""
        ),
        addArguments: [
          key: postContent,
          array: %{value}%,
          value: getSelfProp(
            %{self}%,
            sprintf(
              postContent-%s,
              [extract(%{value}%, lang)]
            )
          )
        ]
      ),
      applyFunctionField(
        function: arrayAddItem(
          array: [],
          value: ""
        ),
        addArguments: [
          key: header,
          array: %{value}%,
          value: sprintf(
            string: "<p>Hi %s, we published this post on %s, enjoy!</p>",
            values: [
              extract(%{value}%, name),
              getSelfProp(%{self}%, postDate)
            ]
          )
        ]
      )
    >
  >;

  getSelfProp(%{self}%, userPostData)@translatedUserPostProps<
    forEach(
      if: not(
        equals(
          extract(%{value}%, lang),
          en
        )
      )
    )<
      underJSONObjectProperty(
        path: header,
        appendExpressions: {
          toLang: extract(%{value}%, lang)
        }
      )<
        translateMultiple(
          from: en,
          to: %{toLang}%,
          oneLanguagePerField: true,
          override: true
        )
      >
    >
  >;

  getSelfProp(%{self}%,translatedUserPostProps)@emails<
    forEach<
      applyFunctionField(
        function: arrayAddItem(
          array: [],
          value: []
        ),
        addArguments: [
          key: content,
          array: %{value}%,
          value: concat([
            extract(%{value}%, header),
            extract(%{value}%, postContent)
          ])
        ]
      ),
      applyFunctionField(
        function: arrayAddItem(
          array: [],
          value: []
        ),
        addArguments: [
          key: to,
          array: %{value}%,
          value: extract(%{value}%, email)
        ]
      ),
      applyFunctionField(
        function: arrayAddItem(
          array: [],
          value: []
        ),
        addArguments: [
          key: subject,
          array: %{value}%,
          value: "PoP API example :)"
        ]
      ),
      sendByEmail
    >
  >
```

<a href='https://newapi.getpop.org/api/graphql/?postId=1&query=post({id:$postId})@post.content|dateStr(d/m/Y)@date,getJSON("https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions")@userList|arrayUnique(extract(getSelfProp(%{self}%,userList),lang))@userLangs|extract(getSelfProp(%{self}%,userList),email)@userEmails|arrayOrObjectFill(getJSON(sprintf("https://newapi.getpop.org/users/api/rest/?query=name|email%26emails[]=%s",[arrayJoin(getSelfProp(%{self}%,userEmails),"%26emails[]=")])),getSelfProp(%{self}%,userList),email)@userData;post({id:$postId})@post<copyRelationalResults([content,date],[postContent,postDate])>;getSelfProp(%{self}%,postContent)@postContent<translateMultiple(from:en,to:arrayDiff([getSelfProp(%{self}%,userLangs),[en]])),renameProperty(postContent-en)>|getSelfProp(%{self}%,userData)@userPostData<forEach<applyFunctionField(function:arrayAddItem(array:[],value:""),addArguments:[key:postContent,array:%{value}%,value:getSelfProp(%{self}%,sprintf(postContent-%s,[extract(%{value}%,lang)]))]),applyFunctionField(function:arrayAddItem(array:[],value:""),addArguments:[key:header,array:%{value}%,value:sprintf(string:"<p>Hi %s, we published this post on %s,enjoy!</p>",values:[extract(%{value}%,name),getSelfProp(%{self}%,postDate)])])>>;getSelfProp(%{self}%,userPostData)@translatedUserPostProps<forEach(if:not(equals(extract(%{value}%,lang),en)))<underJSONObjectProperty(path:header,appendExpressions:{toLang:extract(%{value}%,lang)})<translateMultiple(from:en,to:%{toLang}%,oneLanguagePerField:true,override:true)>>>;getSelfProp(%{self}%,translatedUserPostProps)@emails<forEach<applyFunctionField(function:arrayAddItem(array:[],value:[]),addArguments:[key:content,array:%{value}%,value:concat([extract(%{value}%,header),extract(%{value}%,postContent)])]),applyFunctionField(function:arrayAddItem(array:[],value:[]),addArguments:[key:to,array:%{value}%,value:extract(%{value}%,email)]),applyFunctionField(function:arrayAddItem(array:[],value:[]),addArguments:[key:subject,array:%{value}%,value:"PoP API example :)"]),sendByEmail>>'>View query results</a>

**Step-by-step description of the solution:**

[leoloso.com/posts/demonstrating-pop-api-graphql-on-steroids/](https://leoloso.com/posts/demonstrating-pop-api-graphql-on-steroids/) -->

## PHP versions

Requirements:

- PHP 8.1+ for development
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
