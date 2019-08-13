![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

<!--# PoP — “Platform of Platforms”-->
# PoP

<!--
## 🤔 Notice

PoP's codebase is currently being migrated to Composer components. PoP 1.0 will be released once the migration is complete. Contributors are welcome! [Find out more](#codebase-migration).
-->

## Install

> **Note:** The instructions below will install [PoP API for WordPress](https://github.com/leoloso/pop-api-wp). Other configurations (eg: for other CMSs, to set-up a website instead of an API, and others) are coming soon.

### Installing PoP on an existing WordPress site

Via a WordPress plugin:

_Coming soon..._

-----

Via Composer:

1. Make sure your `composer.json` file has the configuration below (since PoP is currently under development, there are no releases yet, so the project must accept minimum stability `"dev"`):

```javascript
{
    ...
    "minimum-stability": "dev",
    "prefer-stable": true,
    ...
}
```

2. Add the following mandatory packages to the `require` section of your `composer.json` file:

```bash
{
    ...
    "require": {
        ...
        "getpop/engine-symfony": "dev-master",
        "getpop/engine-wp": "dev-master",
        ...
    }
    ...
}
```

3. (Suggested) Add the following packages to the `require` section of your `composer.json` file:

```bash
{
    ...
    "require": {
        ...
        "getpop/posts-wp": "dev-master",
        "getpop/postmeta-wp": "dev-master",
        "getpop/users-wp": "dev-master",
        "getpop/usermeta-wp": "dev-master",
        "getpop/pages-wp": "dev-master",
        "getpop/comments-wp": "dev-master",
        "getpop/commentmeta-wp": "dev-master",
        "getpop/taxonomies-wp": "dev-master",
        "getpop/taxonomymeta-wp": "dev-master",
        "getpop/taxonomyquery-wp": "dev-master",
        ...
    }
    ...
}
```

4. Run `composer update`:

```bash
$ composer update
```

5. Wait for a few minutes ☕️😁

6. ✅ Check that the PoP API works by loading in your site: `/posts/?action=api&datastructure=rest` (assuming that package `"getpop/posts-wp"` was added)

### Creating a new WordPress site with PoP installed

Via [Composer](https://getcomposer.org) and [WP-CLI](https://wp-cli.org/) (installed globally):

1. Create the [WordPress database and user](https://wordpress.org/support/article/how-to-install-wordpress/#step-2-create-the-database-and-a-user)
2. Configure WordPress through environment variables: 

> Copy the code below to an editor, replace all values (such as `{YOUR_SITE_DB_NAME}`) with your own values, and then either paste it on the terminal to execute, or save it in file "~/.bash_profile" and then execute `source ~/.bash_profile`.

```bash
export DB_NAME={YOUR_SITE_DB_NAME} #eg: database
export DB_USER={YOUR_SITE_DB_USER} #eg: admin
export DB_PASSWORD={YOUR_SITE_DB_PASSWORD} #eg: sADF!kl9diq@#Sjfk
export DB_HOST={YOUR_SITE_DB_HOST} #eg: 127.0.0.1
export SITE_URL_WITHOUT_HTTP={YOUR_SITE_URL_WITHOUT_HTTP} #eg: localhost
export SITE_URL_WITH_HTTP={YOUR_SITE_URL_WITH_HTTP} #eg: http://localhost
export SITE_NAME="{YOUR_SITE_NAME}" #eg: "My awesome website"
export ADMIN_USER={ADMIN_USER} #eg: admin
export ADMIN_PASSWORD={ADMIN_PASSWORD} #eg: JKo$@sfjASD00w
export ADMIN_EMAIL={ADMIN_EMAIL} #eg: pedro@example.com
```

To set the SALT keys there are two alternatives:

a. Set random values through environment variable `SHUFFLE_SALT_KEYS`:

```bash
export SHUFFLE_SALT_KEYS=true
```

b. Set the corresponding values directly:

```bash
# Obtain random values from https://api.wordpress.org/secret-key/1.1/salt
export AUTH_KEY={YOUR_AUTH_KEY}
export SECURE_AUTH_KEY={YOUR_SECURE_AUTH_KEY}
export LOGGED_IN_KEY={YOUR_LOGGED_IN_KEY}
export NONCE_KEY={YOUR_NONCE_KEY}
export AUTH_SALT={YOUR_AUTH_SALT}
export SECURE_AUTH_SALT={YOUR_SECURE_AUTH_SALT}
export LOGGED_IN_SALT={YOUR_LOGGED_IN_SALT}
export NONCE_SALT={YOUR_NONCE_SALT}
```

3. Bootstrap a new project from this repo:

```bash
composer create-project leoloso/pop-api-wp
```

4. Wait for a few minutes ☕️😁

5. ✅ The site is installed under:

- 👉 WordPress site: `{YOUR_SITE_URL_WITH_HTTP}`
- 👉 WordPress admin: `{YOUR_SITE_URL_WITH_HTTP}`/wp/wp-admin/
- 👉 PoP API: `{YOUR_SITE_URL_WITH_HTTP}/posts/?action=api&datastructure=rest` (REST for posts)

### Configure application options (optional)

PoP can be configured through environment variables. To make it convenient during development, PoP relies on [Symfony's Dotenv component](https://symfony.com/doc/current/components/dotenv.html) to define environment variables through file `config/.env`. You can create or further edit this file, and create additional localized `.env` files (such as `.env.local`, as detailed in the [component's documentation](https://symfony.com/doc/current/components/dotenv.html)).

## Usage

> **Note:** The usage below belong to [PoP API for WordPress](https://github.com/leoloso/pop-api-wp). Other configurations (eg: for other CMSs, to set-up a website instead of an API, and others) are coming soon.

For the **REST-compatible API**, add parameters `action=api&datastructure=rest` to the endpoint URL. 

For the **GraphQL-compatible API**, add parameters `action=api&datastructure=graphql` to the endpoint URL, and parameter `fields` with the fields to retrieve (using a [custom dot notation](https://github.com/leoloso/PoP#defining-what-data-to-fetch-through-fields)) from the list of fields defined below. In addition, a field may have [modifiers](https://github.com/leoloso/PoP#field-modifiers) to modify its results.

For the **PoP native API**, add parameters `action=api` to the endpoint URL, and parameter `fields` similar to GraphQL.

----

Currently, the API supports the following entities and fields:

### Posts

**Endpoints**:

_List of posts:_

- **REST:** [/posts/?action=api&datastructure=rest](https://nextapi.getpop.org/posts/?action=api&datastructure=rest)
- **GraphQL:** [/posts/?action=api&datastructure=graphql](https://nextapi.getpop.org/posts/?action=api&datastructure=graphql&fields=id|title|url)
- **PoP native:** [/posts/?action=api](https://nextapi.getpop.org/posts/?action=api&fields=id|title|url)

_Single post:_

- **REST:** [/{SINGLE-POST-URL}/?action=api&datastructure=rest](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/?action=api&datastructure=rest) 
- **GraphQL:** [/{SINGLE-POST-URL}/?action=api&datastructure=graphql](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/?action=api&datastructure=graphql&fields=id|title|date|content)
- **PoP native:** [/{SINGLE-POST-URL}/?action=api](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/?action=api&fields=id|title|date|content)

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (modifiers)</th><th>Relational (modifiers)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>post-type<br/>published<br/>not-published<br/>title<br/>content<br/>url<br/>endpoint<br/>excerpt<br/>status<br/>is-draft<br/>date<br/>datetime<br/>comments-url<br/>comments-count<br/>has-comments<br/>published-with-comments<br/>cats<br/>cat<br/>cat-name<br/>cat-slugs<br/>tag-names</td><td>comments<br/>tags<br/>author</td></tr>
</tbody>
</table>

**Examples:**

_List of posts + author data:_<br/>[id|title|date|url,author.id|name|url,author.posts.id|title|url](https://nextapi.getpop.org/posts/?action=api&datastructure=graphql&fields=id|title|date|url,author.id|name|url,author.posts.id|title|url)

_Single post + tags, comments and comment author info:_<br/>[id|title|cat-slugs,tags.id|slug|count|url,comments.id|content|date,comments.author.id|name|url](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/?action=api&datastructure=graphql&fields=id|title|cat-slugs,tags.id|slug|count|url,comments.id|content|date,comments.author.id|name|url)

### Users

**Endpoints:**

_List of users:_

- **REST:** [/users/?action=api&datastructure=rest](https://nextapi.getpop.org/users/?action=api&datastructure=rest)
- **GraphQL:** [/users/?action=api&datastructure=graphql](https://nextapi.getpop.org/users/?action=api&datastructure=graphql&fields=id|name|url)
- **PoP native:** [/users/?action=api](https://nextapi.getpop.org/users/?action=api&fields=id|name|url)

_Author:_

- **REST:** [/{AUTHOR-URL}/?action=api&datastructure=rest](https://nextapi.getpop.org/author/themedemos/?action=api&datastructure=rest) 
- **GraphQL:** [/{AUTHOR-URL}/?action=api&datastructure=graphql](https://nextapi.getpop.org/author/themedemos/?action=api&datastructure=graphql&fields=id|name|description)
- **PoP native:** [/{AUTHOR-URL}/?action=api](https://nextapi.getpop.org/author/themedemos/?action=api&fields=id|name|description)

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (modifiers)</th><th>Relational (modifiers)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>username<br/>user-nicename<br/>nicename<br/>name<br/>display-name<br/>firstname<br/>lastname<br/>email<br/>url<br/>endpoint<br/>description<br/>website-url</td><td>posts (limit, offset, order, orderby, search)</td></tr>
</tbody>
</table>

**Examples:**

_List of users + up to 2 posts for each, ordered by date:_<br/>[id|name|url,posts(limit:2;order:desc;orderby:date).id|title|url|date](https://nextapi.getpop.org/users/?action=api&datastructure=graphql&fields=id|name|url,posts(limit:2;order:desc;orderby:date).id|title|url|date)

_Author + all posts, with their tags and comments, and the comment author info:_<br/>[id|name|url,posts.id|title,posts.tags.id|slug|count|url,posts.comments.id|content|date,posts.comments.author.id|name](https://nextapi.getpop.org/author/themedemos/?action=api&datastructure=graphql&fields=id|name|url,posts.id|title,posts.tags.id|slug|count|url,posts.comments.id|content|date,posts.comments.author.id|name)

### Comments

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (modifiers)</th><th>Relational (modifiers)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>content<br/>author-name<br/>author-url<br/>author-email<br/>approved<br/>type<br/>date</td><td>author<br/>post<br/>post-id<br/>parent</td></tr>
</tbody>
</table>

**Examples:**

_Single post's comments:_<br/>[comments.id|content|date|type|approved|author-name|author-url|author-email](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/?action=api&datastructure=graphql&fields=comments.id|content|date|type|approved|author-name|author-url|author-email)

### Tags

**Endpoints:**

_List of tags:_

- **REST:** [/tags/?action=api&datastructure=rest](https://nextapi.getpop.org/tags/?action=api&datastructure=rest)
- **GraphQL:** [/tags/?action=api&datastructure=graphql](https://nextapi.getpop.org/tags/?action=api&datastructure=graphql&fields=id|slug|count|url)
- **PoP native:** [/tags/?action=api](https://nextapi.getpop.org/tags/?action=api&fields=id|slug|count|url)

_Tag:_

- **REST:** [/{TAG-URL}/?action=api&datastructure=rest](https://nextapi.getpop.org/tag/html/?action=api&datastructure=rest) 
- **GraphQL:** [/{TAG-URL}/?action=api&datastructure=graphql](https://nextapi.getpop.org/tag/html/?action=api&datastructure=graphql&fields=id|name|slug|count)
- **PoP native:** [/{TAG-URL}/?action=api](https://nextapi.getpop.org/tag/html/?action=api&fields=id|name|slug|count)

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (modifiers)</th><th>Relational (modifiers)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>symbol<br/>symbolnamedescription<br/>namedescription<br/>url<br/>endpoint<br/>symbolname<br/>name<br/>slug<br/>term_group<br/>term_taxonomy_id<br/>taxonomy<br/>description<br/>count</td><td>parent<br/>posts (limit, offset, order, orderby, search)</td></tr>
</tbody>
</table>

**Examples:**

_List of tags + all their posts ordered by title, their comments, and the comment authors:_<br/>[id|slug|count|url,posts(orderby:title;order:asc).id|title|url|date](https://nextapi.getpop.org/tags/?action=api&datastructure=graphql&fields=id|slug|count|url,posts(orderby:title;order:asc).id|title|url|date)

_Tag + all their posts, their comments and the comment authors:_<br/>[id|slug|count|url,posts.id|title,posts.comments.content|date,posts.comments.author.id|name|url](https://nextapi.getpop.org/tag/html/?action=api&datastructure=graphql&fields=id|slug|count|url,posts.id|title,posts.comments.content|date,posts.comments.author.id|name|url)

### Pages

**Endpoints:**

_Page:_

- **REST:** [/{PAGE-URL}/?action=api&datastructure=rest](https://nextapi.getpop.org/about/?action=api&datastructure=rest)
- **GraphQL:** [/{PAGE-URL}/?action=api&datastructure=graphql](https://nextapi.getpop.org/about/?action=api&datastructure=graphql&fields=id|title|content)
- **PoP native:** [/{PAGE-URL}/?action=api](https://nextapi.getpop.org/about/?action=api&fields=id|title|content)

**GraphQL fields:**

<table>
<thead>
<tr><th>Property (modifiers)</th><th>Relational (modifiers)</th></tr>
</thead>
<tbody>
<tr valign="top"><td>id<br/>title<br/>content<br/>url</td><td>&nbsp;</td></tr>
</tbody>
</table>

**Examples:**

_Page:_<br/>[id|title|content|url](https://nextapi.getpop.org/about/?action=api&datastructure=graphql&fields=id|title|content|url)

## Description

PoP is set of PHP components for building APIs (PoP native or GraphQL/REST-compatible) and, further extending from the API, websites. PoP is components all the way down, implemented partly in the back-end (component hierarchy, data loading, configuration and props), and partly in the front-end (view and reactivity).

<!--Out of the box it provides a data + configuration API, over which it allows to build any type of website. Starting on simple sites, we can progressively install plugins to convert them to more complex applications, such as single-page applications, progressive web apps, social networks, decentralized sites, and others.-->

PoP is composed of several layers, which can be progressively enabled to unlock further capabilities:

1. **A data API:** _(currently implemented ✅)_<br/>For fetching and posting data, in 2 flavours:
    1. **PoP native format:**<br/>A relational database structure, which retrieves object data under the object's ID (thus normalizing all retrieved data), accessible under `/page-url/?output=json` (implicit fields) or `/page-url/?action=api&fields=...` (explicit fields)
    2. **A GraphQL/REST-compatible API:**<br/>A GraphQL-like, query-based data structure which mirrors the query from the client, returning the response in either GraphQL or REST formats, accessible under `/page-url/?action=api&datastructure=rest` (implicit REST fields) or `/page-url/?action=api&datastructure=graphql&fields=...` (explicit GraphQL fields)
2. **A configuration API:** _(coming soon ⚠️)_<br/>The application can retrieve configuration values from the API, avoiding then to hardcode these on client-side files, making the application become more modular and maintainable
3. **Rendering on client-side:** _(coming soon ⚠️)_<br/>Consume the API data through JavaScript templates to render the website in the client
4. **Isomorphic rendering on server-side:** _(coming soon ⚠️)_<br/>Process the JavaScript templates in the server to produce the HTML already in the server

## API foundations

The component-based architecture of the API is based on the following foundations:

1. Everything is a module (or component)
2. The module is its own API
3. Reactivity (based on observing a unique client-side database object)

### 1. Everything is a module

A PoP application contains a top-most module (also called a component) which wraps other modules, which themselves wrap other modules, and so on until reaching the last level:

![In PoP, everything is a module](https://uploads.getpop.org/wp-content/uploads/2018/12/everything-is-a-module.jpg)

Hence, in PoP everything is a module, and the top-most module represents the page.

### 2. The module is its own API

Every module, at whichever level inside the component hierarchy (i.e. the composition of modules starting from the top-most, all the way down to the last level), is independently accessible through the API simply by passing along its module path in the URL: `/page-url/?output=json&modulefilter=modulepaths&modulepaths[]=path-to-the-module`

### 3. Reactivity

Updating database data and configuration data saved in the client throughout the user session makes the layouts using this data be automatically re-rendered.

> Note: Implementation of this feature is still in progress and not yet available

## Progressive/Resilient components

In most libraries/frameworks in the market, the implementation of the concept of components is based on JavaScript. In PoP, a component has a progressive approach, and its implementation spans from back to front-end:

<table>
<thead>
<tr><th>Back-end (PHP)</th><th>Front-end</th></tr>
</thead>
<tbody>
<tr valign="top"><td>
<ul>
<li>Component hierarchy</li>
<li>Data loading</li>
<li>Configuration</li>
<li>Props</li>
</ul>
</td><td>
<ul>
<li>Styles (CSS)</li>
<li>View (Handlebars templates)</li>
<li>Reactivity (vanilla JavaScript)</li>
</ul>
</td></tr>
</tbody>
</table>

**Bonus:** Because the view is implemented through [Handlebars](https://handlebarsjs.com/) templates, which can be loaded both in the client (through the Handlebars runtime) and in the server (through PHP library [LightnCandy](https://github.com/zordius/lightncandy)), then this approach is isomorphic: the same code works on both environments.

## Design goals

PoP's architecture attempts to achieve the following goals:

✅ Support for creating all-purpose APIs, compatible with both REST and GraphQL, and combining the most important benefits from these two APIs

✅ Progressive enhancement: the API becomes an Application

✅ High level of modularity:

- Strict top-down module hierarchy: ancestor modules know their descendants, but not the other way around
- One-way setting of props across modules, from ancestors to descendants
- Configuration values through API, allowing to decouple modules from each other

✅ Minimal effort to produced a maximum output:

- Isomorphism to produce HTML code client and server-side and to be sent as transactional email
- API output easily customized for different applications (website, mobile app, integration with 3rd party apps, etc)
- Native code splitting, A/B testing, client-side state management and layout cache

✅ Clearly decoupled responsibilities among PHP, JavaScript, JavaScript templates and CSS (defining clear responsibilities across team members):

- JavaScript templates for markup
- JavaScript for user interactivity/dynamic functionality
- CSS for styles
- PHP for creating the modules

✅ JavaScript as progressive enhancement (the application works always, even if JavaScript is disabled)

✅ Aggressive caching, implemented across several layers: 

- Pages and configuration in server
- Content and assets through CDN
- Content and assets through service workers and local storage in client

✅ Self documentation: 

- The website is already the documentation for the API
- Component pattern libraries are automatically generated by rendering each module on their own (through `modulefilter=modulepaths&modulepaths[]=path-to-the-module`)

## Open specification

PoP is in the process of decoupling the API specification from the implementation, resulting in the following parts:

1. The API (JSON response) specification
2. PoP Server, to serve content based on the API specification (originally implemented in PHP)
3. PoP.js, to consume the native PoP API in the client
<!--
We will soon release the current implementation of PoP Server and PoP.js:

- PoP Server for WordPress, based on PoP Server for PHP
- PoP.js through vanilla JS and Handlebars templates
-->
Through the open specification, we hope this architecture can be migrated to other technologies (eg: Node.js, Java, .NET, etc), enabling any site implementing the specification to be able to interact with any other such site. 
<!--
### CMS-agnostic

Because it was originally conceived for WordPress, PoP's current implementation is in PHP, which can be perfectly used for other PHP-based CMSs (such as Joomla or Drupal). For this reason, we are transforming the codebase to make PoP become CMS-agnostic, splitting plugins into 2 entities: a generic implementation that should work for every CMS (eg: "pop-engine") and a specific one for WordPress (eg: "pop-engine-wp"), so that only the latter one should be re-implemented for other CMSs. 

> Note: This task is a work in progress and nowhere near completion: plenty of code has been implemented following the WordPress architecture (eg: basing the object model on posts, pages and custom post types), and must be assessed if it is compatible for other CMSs.
-->

## API compatible with GraphQL and REST

The response of the API can use both the REST and GraphQL formats. This way, a PoP API can be used as a drop-in replacement for both REST and GraphQL, providing the benefits of both these APIs at the same time:

- 🤘🏽 No over/under-fetching data (as in GraphQL)
- 🤘🏽 Shape of the response mirrors mirrors the query (as in GraphQL)
- 🤘🏽 Passing parameters to the query nodes, at any depth, for filtering/pagination/formatting/etc (as in GraphQL)
- 💪🏻Server-side caching (as in REST)
- 💪🏻Secure: Not chance of Denial of Service attacks (as in REST)
- 💪🏻Provide default data when no query is provided (as in REST)

In a nutshell: the PoP API supports REST endpoints with GraphQL queries.

### Defining what data to fetch through fields

Through a parameter `datastructure` in the URL we can select if the response must be REST-compatible or GraphQL-compatible. To fetch the data fields, for REST it supports default fields (as in typical REST behaviour), or explicitly querying for the fields, like in GraphQL. For this, the GraphQL query is passed in the URL through parameter `fields`, converted to a "custom dot notation" format:

- Defining the path to a field with `.`
- Separating different nodes with `,`
- Grouping all property fields on a node with `|`

For instance, the following GraphQL query:

```graphql
query {
  id
  title
  url
  content
  comments {
    id
    content
    date
    author {
      id
      name
      url
      posts {
        id
        title
        url
      }
    }
  }
}
```

Is converted to the "custom dot notation" format like this:

```
id|title|url|content,comments.id|content|date,comments.author.id|name|url,comments.author.posts.id|title|url
```

And our endpoint URL becomes:

```
{ENDPOINT-URL}/?action=api&datastructure=graphql&fields=id|title|url|content,comments.id|content|date,comments.author.id|name|url,comments.author.posts.id|title|url
```

### Field modifiers

Similar to GraphQL, a field may have modifiers: an array of `key:value` properties, appended next to the field name enclosed with `()` and separated with `;`, which modify the output from the field. 

For instance, an author's posts can be ordered (`posts(order:asc;orderby:title)`) and limited to a string and number of results (`posts(search:template;limit:3)`), a user avatar can be specified its dimensions in pixels (`avatar(size: 40)`), a share URL can specify the provider (`share-url(provider:facebook)`), and others.

### Examples

**REST:**

- [Retrieving default data (implicit fields)](https://nextapi.getpop.org/en/posts/?action=api&datastructure=rest)
- [Retrieving client-custom data (explicit fields)](https://nextapi.getpop.org/en/posts/?action=api&datastructure=rest&fields=id|title|url|content,comments.id|content|date,comments.author.id|name|url,comments.author.posts.id|title|url)

**GraphQL:**

- [Retrieving client-custom data](https://nextapi.getpop.org/en/posts/?action=api&datastructure=graphql&fields=id|title|url|content,comments.id|content|date,comments.author.id|name|url,comments.author.posts.id|title|url)
- [Returning an author's posts that contain a certain string](https://nextapi.getpop.org/author/themedemos/?action=api&datastructure=graphql&fields=id|name,posts(search:template).id|title|url)
<!--
- [Filtering data in a nested node](https://nextapi.getpop.org/en/posts/?action=api&datastructure=graphql&fields=id|title|url|content,comments.id|content|date,comments.author.id|name|url,comments.author.posts(limit:2;offset:1;search:elephant).id|title|url)
- [Passing attributes to format elements](https://nextapi.getpop.org/en/posts/?action=api&datastructure=graphql&fields=id|title|url|content,comments.id|content|date,comments.author.id|name|url|avatar(size:40)|share-url(provider:facebook)|share-url(provider:twitter),comments.author.posts.id|title|url)
-->

**Note:** Setting parameter `datastructure` to either `graphql` or `rest` formats the response for the corresponding API. If `datastructure` is left empty, the response is the native one for PoP: a relational database structure (see "Data API layer" section below).

## PoP's native API specification

The examples below demonstrate the structure of PoP's native API specification.

### 1. Data API layer

The PoP API, used for retrieving or posting data, is accessible under `/page-url/?output=json` (implicit fields) or `/page-url/?action=api&fields=...` (explicit fields). It represents data the following way:

- Database data is retrieved through a relational structure under section `databases`
- The IDs which are the results for each component are indicated through entry `dbobjectids` (under section `datasetmoduledata`)
- Where to find those results in the database is indicated through entry `dbkeys` (under section `modulesettings`)
- All database data is normalized (i.e. not repeated)

The API response looks like this:

```javascript
{
  databases: {
    primary: {
      posts: {
        1: {
          author: 7, 
          comments: [88]
        },
        2: {
          recommendedby: [5], 
          comments: [23]
        },
      },
      users: {
        5: {
          name: "Leo"
        },
        7: {
          name: "Pedro"
        },
        18: {
          name: "Romualdo"
        }
      },
      comments: {
        23: {
          author: 7, 
          post_id: 2, 
          content: "Great stuff!"
        },
        88: {
          author: 18, 
          post_id: 1, 
          content: "I love this!"
        }
      }
    }
  },
  datasetmoduledata: {
    "topmodule": {
      modules: {
        "datamodule1": {
          dbobjectids: [1], 
        },
        "datamodule2": {
          dbobjectids: [2], 
        }
      }
    }
  },
  modulesettings: {
    "topmodule": {
      modules: {
        "datamodule1": {
          dbkeys: {
            id: "posts",
            author: "users",
            comments: "comments"
          }
        },
        "datamodule2": {
          dbkeys: {
            id: "posts",
            recommendedby: "users",
            comments: "comments"
          }
        }
      }
    }
  }
}
```

### 2. Configuration API layer

In addition to retrieving database data, the API can also return configuration values:

```javascript
{
  modulesettings: {
    "topmodule": {
      modules: {
        "layoutpostmodule": {
          configuration: {
            class: "text-center"
          },
          modules: {
            "titlemodule": {
              configuration: {
                class: "title bg-info",
                htmltag: "h3"
              }
            },
            "postcontentmodule": {
              configuration: {
                maxheight: "400px"
              },
              modules: {
                "authoravatarmodule": {
                  configuration: {
                    class: "img-thumbnail",
                    maxwidth: "50px"
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
```

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

## When to use PoP

Among others, PoP is suitable for the following use cases:

### 👉🏽 As a drop-in replacement for GraphQL and REST

PoP combines the best of both GraphQL and REST: no under or over-fetching or data while supporting server-side cache and not being open to DoS attacks. And because its response is compatible with both of these APIs, it can conveniently replace them on the application with minimal effort.

### 👉🏽 As an API to fetch data, or execute any type of operation

The API for fetching data comes out of the box, and its component-based architecture makes it a breeze for defining what data fields are required.

The API can also be used to to execute any operation supported by the underlying CMS: post data, log in the user, send an email, etc. Since the operation is defined for each module, it is even possible to execute multiple operations in a single request, and have each module get a feedback response on the success of their operation.

### 👉🏽 Reduce the complexity of the application, and maximize the output from a small team

From a single source of truth it is possible to produce HTML on the server-side, client-side and for transactional emails, so only 1 person can handle all of them. In addition, the architecture provides several features out of the box (code splitting, A/B testing, client-side state management and layout cache), so there is no need to implement them as is typically the case. Finally, the database data + configuration API can be used to power more than one application (eg: a website + a mobile app + interact with 3rd party services) with no big effort.

### 👉🏽 Make the application easily maintainable in the long term

PoP modules are focused on a single task or goal. Hence, to modify a functionality, quite likely just a single module needs be updated, and because of the high degree of modularity attained by the architecture, other modules will not be affected. In addition, each module is cleary decoupled through the use of PHP, CSS, JavaScript templates and JavaScript for functionalities, so only the appropriate team members will need to work on the module (for instance, there is no CSS in JS).

### 👉🏽 Avoid having to document your components

Because the website is already the documentation for the API, and component pattern libraries can be automatically generated by rendering each component on their own, PoP reduces the amount of documentation that needs be produced.

### 👉🏽 Robust architecture based on splitting a component's responsibilities into server and client-side

Using components as the building unit of a website has many advantages over other methods, such as through templates. Modern frameworks bring the magic of components to the client-side for functionality (such as JavaScript libraries React and Vue) and for styles through component pattern libraries (such as Bootstrap). PoP splits a component's responsibilities into server-side (props, configuration, other) and client-side (view, reactivity), creating a more resilient and robust architecture.

<!--### Avoid (or lessen) JavaScript fatigue

PoP is not a JavaScript framework, but a framework spanning the server and client-side. While developers can add client-side JavaScript to enhance the application, it is certainly not a requirement, and powerful applications can be created with basic knowledge of JavaScript.-->

### 👉🏽 Implement COPE or similar techniques

[COPE](https://www.programmableweb.com/news/cope-create-once-publish-everywhere/2009/10/13) (Create Once, Publish Everywhere) is a technique for creating several outputs (website, AMP, newsletters, mobile app, etc) from a single source of truth. PoP supports the implementation of COPE or similar techniques, through an array of native features: API supporting configuration values, the creation of alternative component hierarchies, and the possibility to evaluate data and produce a response depending on the context.

### 👉🏽 Decentralize data sources across domains

PoP supports to set a module as lazy, so that its data is loaded from the client instead of the server, and change the domain from which to fetch the data to any other domain or subdomain, on a module by module basis, which enables to split an application into microservices. In addition, PoP allows to establish more than one data source for any module, so a module can aggregate its data from several domains concurrently, as demonstrated by the content aggregator SukiPoP's [feed](https://sukipop.com/en/posts/), [events calendar](https://sukipop.com/en/calendar/) and [user's map](https://sukipop.com/en/users/?format=map).

<!--### Make the most out of cloud services

From the two foundations "everything is a module" and "a module is its own API", we conclude that everything on a PoP site is an API. And since APIs are greatly suitable to take advantage of cloud services (for instance, serving and caching the API response through a CDN), then PoP calls the cloud "home".-->
<!--
## Timeline

Currently, only the 1st layer, data + configuration API, is available in the repository. We are currently working on the 2nd and 3rd layers, client-side rendering and server-side rendering respectively, and these should be ready and available during the 1st quarter of 2019.
-->
<!--
## Motivation

We have been working on it for more than 5 years, and we are still on the early stages of it (or so we hope!) It was created by Leonardo Losoviz as a solution to connect communities together directly from their own websites, offering an alternative to always depending on Facebook and similar platforms.

PoP didn't start straight as a framework, but as a website for connecting environmental movements in Malaysia, called [MESYM](https://www.mesym.com). After developing plenty of social networking features for this website, we became aware that the website code could be abstracted and turned into a framework for implementing any kind of social network. The PoP framework was thus born, after which we launched a few more websites: [TPPDebate](https://my.tppdebate.org) and [Agenda Urbana](https://agendaurbana.org). 

We then worked towards connecting all the platforms together, so each community could own its own data on their website and share it with the other communities, and break away from depending on the mainstream platforms. We implemented the decentralization feature in PoP, and launched the demonstration site [SukiPoP](https://sukipop.com), which aggregates data from these previous websites and enables different community members to interact with each other.

However, at this point PoP was not a progressive framework for building any kind of site, but a framework for building social networks and nothing else. It was all or nothing, certainly not ideal. For this reason, most of 2018 we have been intensively working on transforming PoP into an all-purpose site builder, which led us to design the component-based architecture for the API, split the framework into several layers, and decouple the API specification from the implementation. 

If the open specification succeeds at attracting interest from the development community and eventually gets implemented for other CMSs and technologies, our goal of connecting sites together will have had a big boost. This is the dream that drives us forward and keeps us working long into the night.
-->
## Examples
<!--
Some examples of PoP in the wild:
-->
### PoP API
<!--
You can play with the PoP API here: https://nextapi.getpop.org. Check the following examples:
-->
<!--
**Requesting specific fields:**

In the following links, data for a resource or collection of resources is fetched as typically done through REST; however, through parameter `fields` we can also specify what specific data to retrieve for each resource, avoiding over or underfetching data: 

- A [single post](https://nextapi.getpop.org/posts/a-lovely-tango/?output=json&action=api&fields=title,content,datetime) and a [collection of posts](https://nextapi.getpop.org/posts/?output=json&action=api&fields=title,content,datetime) adding parameter `fields=title,content,datetime`
- A [user](https://nextapi.getpop.org/u/leo/?output=json&action=api&fields=name,username,description) and a [collection of users](https://nextapi.getpop.org/users/?output=json&action=api&fields=name,username,description) adding parameter `fields=name,username,description`

This works for relationships too. For instance, let's say that we want to retrieve a list of posts with fields `"title"` and `"content"`, each post's comments with fields `"content"` and `"date"`, and the author of each comment with fields `"name"` and `"url"`. To achieve this in GraphQL we would implement the following query:

```javascript
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

For PoP, the query is translated into its corresponding "dot syntax" expression, which can then be supplied through parameter `fields`. Querying on a "post" resource, this value is:

```javascript
fields=title,content,comments.content,comments.date,comments.author.name,comments.author.url
```

Or it can be simplified, using `|` to group all fields applied to the same resource:

```javascript
fields=title|content,comments.content|date,comments.author.name|url
```

When executing this query [on a single post](https://nextapi.getpop.org/posts/a-lovely-tango/?output=json&action=api&fields=title|content,comments.content|date,comments.author.name|url) we obtain exactly the required data for all involved resources.
-->
<!--
**PoP, GraphQL and REST-like API:**
-->
We can fetch exactly the required data for all involved resources from a URL, in different formats:

- [PoP native response](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/?action=api&fields=title|content,comments.content|date,comments.author.name|url)
- [GraphQL-compatible response](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/?action=api&fields=title|content,comments.content|date,comments.author.name|url&datastructure=graphql)
- [REST-compatible response](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/?action=api&fields=title|content,comments.content|date,comments.author.name|url&datastructure=rest)

### Application extending from the API

\[The examples below are currently not deployed... Will do so soon...\] The native API can be extended by adding the other layers (configuration, view) to create the application:

- [The homepage](https://nextapi.getpop.org/?output=json&mangled=none&dataoutputmode=combined), [a single post](https://nextapi.getpop.org/posts/a-lovely-tango/?output=json&mangled=none&dataoutputmode=combined), [an author](https://nextapi.getpop.org/u/leo/?output=json&mangled=none&dataoutputmode=combined), [a list of posts](https://nextapi.getpop.org/posts/?output=json&mangled=none&dataoutputmode=combined) and [a list of users](https://nextapi.getpop.org/users/?output=json&mangled=none&dataoutputmode=combined)
- [An event, filtering from a specific module](https://nextapi.getpop.org/events/coldplay-in-london/?output=json&mangled=none&modulefilter=modulepaths&modulepaths[]=pagesectiongroup.pagesection-body.block-singlepost.block-single-content&dataoutputmode=combined)
- A tag, [filtering modules which require user state](https://nextapi.getpop.org/tags/internet/?output=json&mangled=none&modulefilter=userstate&dataoutputmode=combined) and [filtering to bring only a page from a Single-Page Application](https://nextapi.getpop.org/tags/internet/?output=json&mangled=none&modulefilter=page&dataoutputmode=combined)
- [An array of locations, to feed into a typeahead](https://nextapi.getpop.org/locations/?output=json&mangled=none&modulefilter=maincontentmodule&dataoutputmode=combined&datastructure=results)
- Alternative models for the "Who we are" page: [Normal](https://nextapi.getpop.org/who-we-are/?output=json&mangled=none&dataoutputmode=combined), [Printable](https://nextapi.getpop.org/who-we-are/?output=json&mangled=none&thememode=print&dataoutputmode=combined), [Embeddable](https://nextapi.getpop.org/who-we-are/?output=json&mangled=none&thememode=embed&dataoutputmode=combined)
- Changing the module names: [original](https://nextapi.getpop.org/?output=json&mangled=none&dataoutputmode=combined) vs [mangled](https://nextapi.getpop.org/?output=json&dataoutputmode=combined)
- Filtering information: [only module settings](https://nextapi.getpop.org/?output=json&dataoutputitems[]=modulesettings&dataoutputmode=combined&mangled=none), [module data plus database data](https://nextapi.getpop.org/?output=json&dataoutputitems[]=databases&dataoutputitems[]=moduledata&dataoutputmode=combined&mangled=none)

### PoP Sites

> Note: The websites below run on the old API, and will be migrated to the new API soon.

These 2 sites below were built for demonstration purposes, so you are encouraged to play with them (create a random post or event, follow a user, add a comment, etc):

- Social Network [PoP Demo](https://demo.getpop.org)
- Decentralized Social Network [SukiPoP](https://sukipop.com)

These 2 sites are proper, established social networks:

- Malaysia-based environmental platform [MESYM](https://www.mesym.com)
- Buenos Aires-based civic society-activism platform [Agenda Urbana](https://agendaurbana.org)

<!--

## Installation

Have your WordPress instance running (the latest version of WordPress can be downloaded from [here](https://wordpress.org/download/)). Then copy the contents of folders `/mu-plugins` and `/plugins` under `/wp-content/mu-plugins` and `/wp-content/plugins` respectively, and activate the 11 plugins from this repository:

- pop-cmsmodel
- pop-cmsmodel-wp
- pop-engine
- pop-engine-wp
- pop-examplemodules
- pop-queriedobject
- pop-queriedobject-wp
- pop-taxonomy
- pop-taxonomy-wp
- pop-taxonomyquery
- pop-taxonomyquery-wp

The first 4 plugins are needed to produce the PoP API, and the 5th plugin (pop-examplemodules) provides basic implementations of modules for all supported hierarchies (home, author, single, tag, page and 404). 

> Note: this way to install PoP is temporary. We are currently introducing Composer to the codebase, which will provide a more convenient way to install PoP. It should be ready sometime in March 2019.

That's it. You can then access PoP's API by adding parameter `output=json` to any URL on the site: https://yoursite.com/?output=json.

![If adding parameter output=json to your site produces a JSON response, then you got it!](https://uploads.getpop.org/wp-content/uploads/2018/12/api-json-response.png?)

> Note 1: Currently PoP runs in WordPress only. Hopefully, in the future it will be available for other CMSs and technologies too.

> Note 2: Only the API has been released so far; we are currently implementing the client-side and server-side rendering layers, which should be released during the first quarter of 2019.

> Note 3: The retrieved fields are defined in plugin pop-examplemodules. You can explore the contents of this plugin, and modify it to bring more or less data.

-->
<!--
## Configuration

PoP allows the configuration of the following properties, set in file wp-config.php:

`USE_CACHE` (`true`|`false`, default: `false`): Create and re-use a cache of the settings for the requested page.

`COMPACT_RESPONSE_JSON_KEYS` (`true`|`false`, default: `false`): Compress the keys in the JSON response.

`ENABLE_CONFIG_BY_PARAMS` (`true`|`false`, default: `false`): Enable to set the application configuration through URL param "config".

`POP_SERVER_FAILIFMODULESDEFINEDTWICE` (`true`|`false`, default: `false`): Throw an exception if two different modules have the same name.

`POP_SERVER_ENABLEEXTRAURISBYPARAMS` (`true`|`false`, default: `false`): Allow to request extra URIs through URL param "extrauris".

`DISABLE_API` (`true`|`false`, default: `false`): Disable the custom-querying capabilities of the API.

`EXTERNAL_SITES_RUN_SAME_SOFTWARE` (`true`|`false`, default: `false`): Indicate if the external sites from which the origin site is fetching data has the same components installed. In this case, the data can be retrieved using the standard methods. Otherwise, it will be done through the custom-querying API.

`FAIL_IF_SUBCOMPONENT_DATALOADER_IS_UNDEFINED` (`true`|`false`, default: `false`): Whenever switching domain to a field which doesn't have a default dataloader, and without specifying what dataloader to use, throw an exception if `true` or ignore and avoid loading that data if `false`.
-->
<!--
### Decentralization: enabling crossdomain

To have a website consume data coming from other domains, crossdomain access must be allowed. For this, edit your .htaccess file like this:

    <IfModule mod_headers.c>
      SetEnvIf Origin "http(s)?://(.+\.)?(source-website.com|aggregator-website.com)$" AccessControlAllowOrigin=$0
      Header add Access-Control-Allow-Origin %{AccessControlAllowOrigin}e env=AccessControlAllowOrigin

      # Allow for cross-domain setting of cookies, so decentralized log-in also works
      Header set Access-Control-Allow-Credentials true
      Header add Access-Control-Allow-Methods GET
      Header add Access-Control-Allow-Methods POST
    </IfModule>

**Important**: For POST operations to work, we need to make sure the user's browser isn't blocking third-party cookies, otherwise [cross-origin credentialed requests will not work](https://stackoverflow.com/questions/24687313/what-exactly-does-the-access-control-allow-credentials-header-do#24689738). In Chrome, this configuration is set under Settings > Advanced Settings > Privacy > Content Settings > Block third-party cookies and site data.
-->

<!--
### Integration between the Content CDN and Service Workers

To allow the website's service-worker.js be able to cache content coming from the content CDN, access to reading the ETag header must be granted:

    <IfModule mod_headers.c>
      Header add Access-Control-Allow-Headers ETag
      Header add Access-Control-Expose-Headers ETag
    </IfModule>
-->

## Codebase Migration

**Help needed!** 😝

PoP's codebase is currently being migrated to Composer packages. We are welcoming contributors to help with the migration, as to push forward the release date for PoP 1.0.

Currently, many PoP packages are split into 2:

1. The final package
2. A temporary, "migrate" package

The task is to migrate all code from the "migrate" to the final package, converting all code as required. The "migrate" package contains legacy PHP code written several years ago. The migration must introduce modern PHP techniques to the codebase, such as using Composer, dependency injection, autoloading and others. Once the code migration for each package is complete, the corresponding "migrate" package can be deleted.

Migrating the code involves the implementation of the following features:

- Adding package dependencies in `composer.json`
- Identification of services and making them available through [Symfony's DependencyInjection component](https://symfony.com/doc/current/components/dependency_injection.html)
- Adding namespaces for all PHP classes following the [PSR-4](https://www.php-fig.org/psr/psr-4/) convention, as to support [autoloading through Composer](https://getcomposer.org/doc/01-basic-usage.md#autoloading)
- Shortening of classnames (eg: from the current `PoP_Posts_Module_Processor_PostsDataloads` to `Dataloads`, after placing the class under namespace `PoP\Posts\ModuleProcessors`)
- Using `use` statements to import fully-qualified (namespace+classname) classes at the top of the PHP file, to make the code more legible (eg: instead of repeatedly executing `\PoP\Engine\Engine_Vars::getVars()`, we can first import the class with `use PoP\Engine\Engine_Vars;` and then execute `Engine_Vars::getVars()`)

If you want to become involved, or simply want to find out more, please [contact Leo](mailto:leo@getpop.org).

## Documentation

> Note: The documentation is currently pretty lacking. We expect to work on it further after releasing version 1.0

- [Main Concepts](docs/MainConcepts.md)
- [Developer Guide](docs/DeveloperGuide.md)
- [Architecture Design and Implementation](docs/ArchitectureDesignAndImplementation.md)
- [Tutorials](docs/Tutorials.md)

### Development Articles: How PoP works

The following articles give a step-by-step process of how many features in PoP were implemented:

- [Introducing the Component-based API](https://www.smashingmagazine.com/2019/01/introducing-component-based-api/): article describing all the main concepts of this architecture, published on Smashing Magazine.
- [Caching Smartly In The Age Of Gutenberg](https://www.smashingmagazine.com/2018/12/caching-smartly-gutenberg/): Caching mechanism implemented in PoP, allowing to cache pages even when the user is logged-in (to be emulated for Gutenberg)
- [Avoiding The Pitfalls Of Automatically Inlined Code](https://www.smashingmagazine.com/2018/11/pitfalls-automatically-inlined-code/): How PoP generates JS/CSS resources to improve performance
- [Sending Emails Asynchronously Through AWS SES](https://www.smashingmagazine.com/2018/11/sending-emails-asynchronously-through-aws-ses/): Mechanism to send emails through AWS SES implemented for PoP
- [Adding Code-Splitting Capabilities To A WordPress Website Through PoP](https://www.smashingmagazine.com/2018/02/code-splitting-wordpress-pop/): How PoP implements code-splitting of JavaScript files
- [How To Make A Dynamic Website Become Static Through A Content CDN](https://www.smashingmagazine.com/2018/02/dynamic-website-static-content-cdn/): Mechanism implemented for PoP to route dynamic content through a CDN to improve performance
- [Implementing A Service Worker For Single-Page App WordPress Sites](https://www.smashingmagazine.com/2017/10/service-worker-single-page-application-wordpress-sites/): The strategy behind the creation of the service-worker.js file in PoP (when running under WordPress), which powers its offline-first caching strategy

## 🔥 Become involved!

Contributors are welcome! If either you want to get involved, or simply find out more about PoP, simply [send Leo an email](mailto:leo@getpop.org) or [tweet](https://twitter.com/losoviz) 😀❤️.

<!--
## Want to contribute?

Anybody willing to can become involved in the development of PoP. If there is any new development you are interested in implementing, such as integration with this or that plugin, please let us know and we'll be able to assist you. In addition, check the [issues tagged with "help wanted"](https://github.com/leoloso/PoP/issues?q=is%3Aissue+is%3Aopen+label%3A%22help+wanted%22), we will be very happy if you can tackle any of them.

For more info or have a chat, just [contact us](https://getpop.org/en/contact-us/).
-->


















<!-- 
## Linked resources

- [Implementing a module](Link to pop-examplemodules/README.md)
- Documentation:
  - [fieldprocessors](https://getpop.org/en/documentation/...)




-->
<!-- 
Below is a technical summary. For a more in-depth description, please visit [PoP's documentation page](https://getpop.org/en/documentation/overview/).

## What is PoP?

PoP creates [Single-Page Application](https://en.wikipedia.org/wiki/Single-page_application) websites, by combining [Wordpress](https://wordpress.org) and [Handlebars](http://handlebarsjs.com/) into an [MVC architecture](https://en.wikipedia.org/wiki/Model-view-controller) framework:

- Wordpress is the model/back-end
- Handlebars templates are the view/front-end
- the PoP engine is the controller

![How it works](https://uploads.getpop.org/wp-content/uploads/2016/10/Step-5-640x301.png)

## Design principles

1. PoP provides the WordPress website of its own API:

 - Available via HTTP
 - By adding parameter `output=json` to any URL

2. Decentralized

 - All PoP websites can communicate among themselves
 - Fetch/process data in real time

## What can be implemented with it?

- Niche social networks
- Decentralized websites
- Content aggregators
- Server back-end for mobile apps
- Microservices
- APIs for Wordpress websites

## Installation

We are currently creating scripts to automate the installation process, we expect them to be ready around mid-October 2018.

Until then, we provide a zip file including all code (PoP, WordPress and plugins), and a database dump from the [GetPoP Demo website](https://demo.getpop.org/), to set-up this same site in a quick-and-dirty manner in your localhost. Download the files and read the installation instructions [here](https://github.com/leoloso/PoP/blob/master/install/getpop-demo/install.md).

## Configuration

PoP allows the configuration of the following properties, done in file wp-config.php:

- `USE_APPSHELL` (_true_|_false_): Load an empty Application Shell (or appshell), which loads the page content after loading.

- `DISABLE_SERVER_SIDE_RENDERING` (_true_|_false_): Disable producing HTML on the server-side for the first-loaded page.

- `USE_CODE_SPLITTING` (_true_|_false_): Load only the .js and .css that is needed on each page and nothing more.

- `USE_PROGRESSIVE_BOOTING` (_true_|_false_): If doing code splitting, load JS resources on 2 stages: critical ones immediately, and non-critical ones deferred, to lower down the Time to Interactive of the application.

- `GENERATE_BUNDLEGROUP_FILES` and `GENERATE_BUNDLE_FILES` (_true_|_false_): (Only if doing code-splitting) When executing the `/generate-theme/` build script, generate a single bundlegroup and/or a series of bundle files for each page on the website containing all resources it needs.

- `GENERATE_BUNDLE_FILES_ON_RUNTIME` (_true_|_false_): (Only if doing code-splitting) Generate the bundlegroup or bundle files on runtime, so no need to pre-generate these.

- `GENERATE_LOADING_FRAME_RESOURCE_MAPPING` (_true_|_false_): (Only if doing code-splitting) Pre-generate the mapping listing what resources are needed for each route in the application, created when executing the `/generate-theme/` build script.

- `ENQUEUE_FILES_TYPE` (_resource_|_bundle_|_bundlegroup_): (Only if doing code-splitting) Choose how the initial-page resources are loaded:

    - "resource": Load the required resources straight
    - "bundle": through a series of bundle files, each of them comprising up to x resources (defined through constant `BUNDLE_CHUNK_SIZE`)
    - "bundlegroup": through a unique bundlegroup file

- `BUNDLE_CHUNK_SIZE` (_int_): (Only if doing code-splitting) How many resources to pack inside a bundle file. Default: 4.

- `POP_SERVER_TEMPLATERESOURCESINCLUDETYPE` (_header_|_body_|_body-inline_): (Only if doing server-side rendering, code-splitting and enqueue type = "resource") Choose how to include those resources depended by a module (mainly CSS styles):

    - "header": Link in the header
    - "body": Link in the body, right before the module HTML
    - "body-inline": Inline in the body, right before the module HTML

- `POP_SERVER_GENERATERESOURCESONRUNTIME` (_true_|_false_): Allow to extract configuration code from the HTML output and into Javascript files on runtime.

- `USE_MINIFIED_RESOURCES` (_true_|_false_): Include the minified version of .js and .css files.

- `USE_BUNDLED_RESOURCES` (_true_|_false_): (Only if not doing code-splitting) Insert script and style assets from a single bundled file.

- `POP_SERVER_USECDNRESOURCES` (_true_|_false_): Whenever available, use resources from a public CDN.

- `INCLUDE_SCRIPTS_AFTER_HTML` (_true_|_false_): If doing server-side rendering, re-order script tags so that they are included only after rendering all HTML.

- `POP_SERVER_REMOVEDATABASEFROMOUTPUT` (_true_|_false_): If doing server-side rendering, remove all database data from the HTML output.

- `POP_SERVER_TEMPLATEDEFINITION_TYPE` (_0_|_1_|_2_): Allows to replace the name of each module with a base36 number instead, to generate a smaller response (around 40%).

    - 0: Use the original name of each module
    - 1: Use both
    - 2: Use the base36 counter number

- `POP_SERVER_TEMPLATEDEFINITION_CONSTANTOVERTIME` (_true_|_false_): When mangling the template names (template definition type is set to 2), use a database of all template definitions, which will be constant over time and shared among different plugins, to avoid errors in the website from accessed pages (localStorage, Service Workers) with an out-of-date configuration.

- `POP_SERVER_TEMPLATEDEFINITION_USENAMESPACES` (_true_|_false_): If the template definition type is set to 2, then we can set namespaces for each plugin, to add before each template definition. It is needed for decentralization, so that different websites can communicate with each other without conflict, mangling all template definitions the same way. (Otherwise, having different plugins activated will alter the mangling counter, and produce different template definitions).

- `USE_CACHE` (_true_|_false_): Create and re-use a cache of the settings of the requested page.

- `POP_SERVER_COMPACTJSKEYS` (_true_|_false_): Common keys from the JSON code sent to the front-end are replaced with a compact string. Output response will be smaller.

- `USE_LOCAL_STORAGE` (_true_|_false_): Save special loaded-in-the-background pages in localStorage, to not have to retrieve them again (until software version changes).

- `ENABLE_CONFIG_BY_PARAMS` (_true_|_false_): Enable to set the application configuration through URL param "config".

- `DISABLE_JS` (_true_|_false_): Strip the output of all Javascript code.

- `POP_SERVER_USEGENERATETHEMEOUTPUTFILES` (_true_|_false_): Indicates that we are using all the output files produced from running `/generate-theme/` in this environment, namely:

    - resourceloader-bundle-mapping.json
    - resourceloader-generatedfiles.json
    - All `pop-memory/` files

- `LOAD_FRAME_RESOURCES` (_true_|_false_): When generating file `resources.js`, with the list of resources to dynamically load on the client, include those resources initially loaded in the website (through "loading-frame").

### Decentralization: enabling crossdomain

To have a website consume data coming from other domains, crossdomain access must be allowed. For this, edit your .htaccess file like this:

    <IfModule mod_headers.c>
      SetEnvIf Origin "http(s)?://(.+\.)?(source-website.com|aggregator-website.com)$" AccessControlAllowOrigin=$0
      Header add Access-Control-Allow-Origin %{AccessControlAllowOrigin}e env=AccessControlAllowOrigin

      # Allow for cross-domain setting of cookies, so decentralized log-in also works
      Header set Access-Control-Allow-Credentials true
      Header add Access-Control-Allow-Methods GET
      Header add Access-Control-Allow-Methods POST
    </IfModule>

#### Important

For POST operations to work, we need to make sure the user's browser isn't blocking third-party cookies, otherwise [cross-origin credentialed requests will not work](https://stackoverflow.com/questions/24687313/what-exactly-does-the-access-control-allow-credentials-header-do#24689738). In Chrome, this configuration is set under Settings > Advanced Settings > Privacy > Content Settings > Block third-party cookies and site data.

### Integration between the Content CDN and Service Workers

To allow the website's service-worker.js be able to cache content coming from the content CDN, access to reading the ETag header must be granted:

    <IfModule mod_headers.c>
      Header add Access-Control-Allow-Headers ETag
      Header add Access-Control-Expose-Headers ETag
    </IfModule>

## Optimization

_**Important:** Similar to the installation process, there is room for improvement for the optimization process. If you would like to help us, please [check here](https://github.com/leoloso/PoP/issues/49)._

PoP allows to mangle, minify and bundle together all required .css, .js and .tmpl.js files (suitable for PROD environment), both at the plug-in and website levels:

- **At the plug-in level** (it generates 1.js + 1 .tmpl.js + 1.css files per plug-in): execute `bash -x plugins/PLUGIN-NAME/build/minify.sh` for each plugin
- **At the website level** (it generates 1.js + 1 .tmpl.js + 1.css files for the whole website): execute `bash -x themes/THEME-NAME/build/minify.sh` for the theme

Executing the `minify.sh` scripts requires the following software (_I'll welcome anyone proposing a better way to do this_):
 
1. [UglifyJS](https://github.com/mishoo/UglifyJS2)

 To minify (as to reduce the file size of) JS files

2. [UglifyCSS](https://github.com/fmarcia/UglifyCSS)

 To minify (as to reduce the file size of) CSS files

3. [Google's minimizer Min](https://github.com/mrclay/minify)

 To bundle and minify files. The min webserver must be deployed under http://min.localhost/.

The following environment variables are used in `minify.sh`: `POP_APP_PATH`, `POP_APP_MIN_PATH` and `POP_APP_MIN_FOLDER`. To set their values, for Mac, execute `sudo nano ~/.bash_profile`, then add and save:
    
      export POP_APP_PATH=path to your website (eg: "/Users/john/Sites/PoP")
      export POP_APP_MIN_PATH=path to Google's min website (eg: "/Users/john/Sites/min")
      export POP_APP_MIN_FOLDER=path to folder in min, used for copy files to minimize (eg: "PoP", with the folder being /Users/john/Sites/min/PoP/)

The `minify.sh` script copies all files to minimize under folder `POP_APP_MIN_FOLDER`, from where it minimizes them. The structure of this folder must be created in advance, as follows:
 
 for each theme:
  
      apps/THEME-NAME/css/
      apps/THEME-NAME/js/
      themes/THEME-NAME/css/
      themes/THEME-NAME/js/
     
 for each plug-in:
  
      plugins/PLUGIN-NAME/css/
      plugins/PLUGIN-NAME/js/

## Want to help?

We are looking for developers who want to become involved. Check here the issues we need your help with:

https://github.com/leoloso/PoP/issues?q=is%3Aissue+is%3Aopen+label%3A%22help+wanted%22

### Many thanks to BrowserStack!

Open to Open Source projects for free, PoP uses the Live, Web-Based Browser Testing provided by [BrowserStack](https://www.browserstack.com/).

![BrowserStack](http://www.softcrylic.com/wp-content/uploads/2017/03/browser-stack.png)

-->