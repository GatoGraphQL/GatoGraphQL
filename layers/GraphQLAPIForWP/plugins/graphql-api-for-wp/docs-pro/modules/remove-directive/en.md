# Remove Output Directive

Add directive `@remove` to remove the output of a field from the response.

## Description

The GraphQL spec indicates that the GraphQL response needs to match exactly the shape of the query. However, in certain circumstances we'd rather avoid sending back the response of the field, because:

- We already know what it is, and by not sending it again we can boost performance
- It contains sensitive information (such as login credentials)
- An empty field can be distinguished from a `null` value

## How to use

Directive `@remove` has argument `condition`, with which we can specify under what condition to remove the field. It has 3 possible values:

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

## Examples

Let's say we want to retrieve some specific data from an external REST API endpoint, and we don't need the rest of the data. We can then use `@remove` to make the response payload smaller, thus boosting performance:

- Use field `_sendJSONObjectItemHTTPRequest` (from the **Send HTTP Request Fields** module) to connect to the REST API
- Process this data to extract the needed piece of information (via **Field to Input** and the `_objectProperty` field from **Function Fields**)
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

**Please notice:** `@remove` takes place at the very end of the resolution of all the fields under the same node. That's why, in the query above, the field `renderedTitle` is processed before field `postData` is `@remove`d.

---

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

## GraphQL spec

This functionality is currently not part of the GraphQL spec, but it has been requested:

- <a href="https://github.com/graphql/graphql-spec/issues/275#issuecomment-338538911" target="_blank">Issue #275 - @include(unless null) ?</a>
- <a href="https://github.com/graphql/graphql-spec/issues/766" target="_blank">Issue #766 - GraphQL query: skip value field if null</a>
