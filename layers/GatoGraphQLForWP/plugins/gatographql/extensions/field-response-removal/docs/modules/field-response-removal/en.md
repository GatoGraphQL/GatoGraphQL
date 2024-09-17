# Field Response Removal

Addition of the `@remove` directive to the GraphQL schema, which removes the output of a field from the response.

## Description

The GraphQL spec indicates that the GraphQL response needs to match exactly the shape of the query. However, in certain circumstances we'd rather avoid sending back the response of the field, because:

- We already know what it is, and by not sending it again we can boost performance
- It contains sensitive information (such as login credentials)
- An empty field can be distinguished from a `null` value

By adding `@remove` to the field, it will not be printed in the response.

In the query below (that uses the **PHP Functions via Schema** and **HTTP Client** extensions), we generate the URL to send an HTTP request to, by concatenating the site domain and the REST API endpoint. As the values of these "components" are not of interest to us, there is no need to print them in the response, and we can `@remove` them:

```graphql
query {
  siteURL: optionValue(name: "siteurl")
    @remove

  requestURL: _sprintf(
    string: "%s/wp-json/wp/v2/comments/11/?_fields=id,content,date",
    values: [$__siteURL]
  )
    @remove

  _sendJSONObjectItemHTTPRequest(
    input: {
      url: $__requestURL
    }
  )
}
```

...producing (notice that fields `siteURL` and `requestURL` are not in the response):

```json
{
  "data": {
    "_sendJSONObjectItemHTTPRequest": {
      "id": 11,
      "date": "2020-12-12T04:07:36",
      "content": {
        "rendered": "<p>Btw, I really like this stuff<\/p>\n"
      }
    }
  }
}
```

We can also tell directive `@remove` to conditionally remove the value, if a condition is met. Argument `condition` can receive 3 possible values:

- `ALWAYS` (default value): Remove it always
- `IS_NULL`: Remove it whenever the value is `null`
- `IS_EMPTY`: Remove it whenever the value is empty

For instance, in the query below, when a post does not have a featured image, field `featuredImage` will have value `null`. By adding `@remove(condition: IS_NULL)`, this value will not be added to the response:

```graphql
query {
  posts {
    title
    featuredImage @remove(condition: IS_NULL) {
      src
    }
  }
}
```

...producing:

```json
{
  "data": {
    "posts": [
      {
        "title": "Hello world!"
      },
      {
        "title": "Nested mutations are a must have",
        "featuredImage": {
          "src": "https:\/\/gatographql.lndo.site\/wp-content\/uploads\/GatoGraphQL-logo.png"
        }
      },
      {
        "title": "Customize the schema for each client"
      }
    ]
  }
}
```

## Examples

### Remove unneeded data from an external API

Let's say we want to retrieve some specific data from an external REST API endpoint, and we don't need the rest of the data. We can then use `@remove` to make the response payload smaller, thus boosting performance:

- Use field `_sendJSONObjectItemHTTPRequest` (from the **HTTP Client** extension) to connect to the REST API
- Process this data to extract the needed piece of information (via **Field to Input** and the `_objectProperty` field from **PHP Function via Schema**)
- `@remove` the original data from the REST endpoint

This query ties everything together:

```graphql
{
  postData: _sendJSONObjectItemHTTPRequest(
    url: "https://newapi.getpop.org/wp-json/wp/v2/posts/1"
  ) @remove
  renderedTitle: _objectProperty(
    object: $__postData,
    by: {
      path: "title.rendered"
    }
  )
}
```

In the response to this query, field `postData` has been removed:

```json
{
  "data": {
    "renderedTitle": "Hello world!"
  }
}
```

<!-- **Please notice:** `@remove` takes place at the very end of the resolution of all the fields under the same node. That's why, in the query above, the field `renderedTitle` is processed before field `postData` is `@remove`d. -->

### Avoid printing user credentials

This example connects to the GitHub API to retrieve the artifacts available in a private repository, and avoids printing the user's credentials in the response:

```graphql
query RetrieveGitHubActionArtifacts(
  $repoOwner: String!
  $repoProject: String!
) {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
    @remove

  # Create the authorization header to send to GitHub
  authorizationHeader: _sprintf(
    string: "Bearer %s"
    # "Field to Input" feature to access value from the field above
    values: [$__githubAccessToken]
  )
    @remove

  # Create the authorization header to send to GitHub
  githubRequestHeaders: _echo(
    value: [
      { name: "Accept", value: "application/vnd.github+json" }
      { name: "Authorization", value: $__authorizationHeader }
    ]
  )
    @remove

  githubAPIEndpoint: _sprintf(
    string: "https://api.github.com/repos/%s/%s/actions/artifacts"
    values: [$repoOwner, $repoProject]
  )

  # Use the field from "Send HTTP Request Fields" to connect to GitHub
  gitHubArtifactData: _sendJSONObjectItemHTTPRequest(
    input: {
      url: $__githubAPIEndpoint
      options: { headers: $__githubRequestHeaders }
    }
  )
}
```
<!-- 
## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-feature-bundled-extensions/docs/modules/all-feature-bundled-extensions/en.md)
- [“Caching” Bundle](../../../../../bundle-extensions/caching/docs/modules/caching/en.md)
- [“Deprecation” Bundle](../../../../../bundle-extensions/deprecation/docs/modules/deprecation/en.md)
- [“Selective Content Import, Export & Sync for WordPress” Bundle](../../../../../bundle-extensions/selective-content-import-export-and-sync-for-wordpress/docs/modules/selective-content-import-export-and-sync-for-wordpress/en.md)
- [“Simplest WordPress Content Translation” Bundle](../../../../../bundle-extensions/simplest-wordpress-content-translation/docs/modules/simplest-wordpress-content-translation/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md)
- [“Versatile WordPress Request API” Bundle](../../../../../bundle-extensions/versatile-wordpress-request-api/docs/modules/versatile-wordpress-request-api/en.md) -->
