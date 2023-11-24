# Distributing content from an upstream to multiple downstream sites

Let's say that a media company has a network of WordPress sites for different regions, with every news article being published on a site or not only if it's suitable for that region.

For this situation, it makes sense to implement an architecture where:

- All content is published to (and edited in) a single upstream WordPress site, which acts as the single source of truth for content
- Suitable content is distributed to (but not edited in) each of the regional downstream WordPress sites

This recipe will demonstrate how to implement this architecture, with the upstream WordPress site needing to have the relevant Gato GraphQL extensions active, while the downstream sites need only have the free Gato GraphQL plugin.

## GraphQL query to synchronize content from upstream to downstream sites

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

_(For the downstream sites only)_ For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

The GraphQL query below is executed on the upstream WordPress site, to synchronize the content of the updated post to the relevant downstream sites, using the post slug as the common identifier across sites.

(The query can be adapted to also synchronize the other properties -tags, categories, author and featured image-, as explained in the previous recipe.)

The query includes transactional logic, so that whenever the update fails on any downstream site, whether because the HTTP request failed (as when the server is down) or because the GraphQL query produced errors (as if there is no post with the provided slug), the mutation is then reverted on all downstream sites.

To revert the state, variable `$previousPostContent` must be provided. We can pass this value by hooking on the `post_updated` WordPress action, upon which the GraphQL query is executed (as explained in a previous recipe).

The query does the following:

- It receives the slug of the updated post, and its new and previous content
- It retrieves the meta property `"downstream_domains"` from the post, which contains an array with the domains of the downstream sites that the post must be distributed to
- If the meta property does not exist (i.e. it has value `null`), it then retrieves option `"downstream_domains"` from the `wp_options` table, which contains the list of all the downstream domains
- It logs the user into each of the downstream sites (using the same `$username` and `$userPassword`, for simplicity) and executes the mutation to update the post content
- If any downstream site produces an error, the mutation is reverted on all downstream sites

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  initVariablesWithFalse: _echo(value: false)
    @export(as: "requestProducedErrors")
    @export(as: "anyErrorProduced")
    @export(as: "hasDownstreamDomains")
    @remove
}

query GetCustomDownstreamDomains($postSlug: String!)
  @depends(on: "InitializeDynamicVariables")
{
  post(by: { slug: $postSlug }, status: any)
    @fail(
      message: "There is no post in the upstream site with the provided slug"
      data: {
        slug: $postSlug
      }
    )
  {
    customDownstreamDomains: metaValues(key: "downstream_domains")
      @export(as: "downstreamDomains")

    hasDefinedCustomDownstreamDomains: _notNull(value: $__customDownstreamDomains)
      @export(as: "hasDefinedCustomDownstreamDomains")
      @remove

    hasCustomDownstreamDomains: _notEmpty(value: $__customDownstreamDomains)
      @export(as: "hasDownstreamDomains")
  }

  isMissingPostInUpstream: _isNull(value: $__post)
    @export(as: "isMissingPostInUpstream")
}

query GetAllDownstreamDomains
  @depends(on: "GetCustomDownstreamDomains")
  @skip(if: $isMissingPostInUpstream)
  @skip(if: $hasDefinedCustomDownstreamDomains)
{
  allDownstreamDomains: optionValues(name: "downstream_domains")
    @export(as: "downstreamDomains")

  hasAllDownstreamDomains: _notEmpty(value: $__allDownstreamDomains)
    @export(as: "hasDownstreamDomains")
}

############################################################
# (By default) Append "/graphql" to the domain, to point
# to that site's GraphQL single endpoint
############################################################
query ExportDownstreamGraphQLEndpointsAndQuery(
  $endpointPath: String! = "/graphql"
)
  @depends(on: "GetAllDownstreamDomains")
  @skip(if: $isMissingPostInUpstream)
  @include(if: $hasDownstreamDomains)
{
  downstreamGraphQLEndpoints: _echo(value: $downstreamDomains)
    @underEachArrayItem(
      passValueOnwardsAs: "domain"
    )
      @strAppend(string: $endpointPath)
    @export(as: "downstreamGraphQLEndpoints")

  query: _echo(value: """
    
mutation LoginUserAndUpdatePost(
  $username: String!
  $userPassword: String!
  $postSlug: String!
  $postContent: String!
) {
  loginUser(by: {
    credentials: {
      usernameOrEmail: $username,
      password: $userPassword
    }
  }) {
    userID
  }

  post(by: {slug: $postSlug})
    @fail(
      message: "There is no post in the downstream site with the provided slug"
      data: {
        slug: $postSlug
      }
    )
  {
    update(input: {
      contentAs: { html: $postContent },
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        slug
        rawContent
      }
    }
  }
}

    """
  )
    @export(as: "query")
    @remove
}

query ExportSendGraphQLHTTPRequestInputs(
  $username: String!
  $userPassword: String!
  $postSlug: String!
  $newPostContent: String!
)
  @depends(on: "ExportDownstreamGraphQLEndpointsAndQuery")
  @skip(if: $isMissingPostInUpstream)
  @include(if: $hasDownstreamDomains)
{
  sendGraphQLHTTPRequestInputs: _echo(value: $downstreamGraphQLEndpoints)
    @underEachArrayItem(
      passValueOnwardsAs: "endpoint"
    )
      @applyField(
        name: "_echo",
        arguments: {
          value: {
            endpoint: $endpoint,
            query: $query,
            variables: [
              {
                name: "username",
                value: $username
              },
              {
                name: "userPassword",
                value: $userPassword
              },
              {
                name: "postSlug",
                value: $postSlug
              },
              {
                name: "postContent",
                value: $newPostContent
              }
            ]
          }
        },
        setResultInResponse: true
      )
    @export(as: "sendGraphQLHTTPRequestInputs")
    @remove
}

query SendGraphQLHTTPRequests
  @depends(on: "ExportSendGraphQLHTTPRequestInputs")
  @skip(if: $isMissingPostInUpstream)
  @include(if: $hasDownstreamDomains)
{
  downstreamGraphQLResponses: _sendGraphQLHTTPRequests(
    inputs: $sendGraphQLHTTPRequestInputs
  )
    @export(as: "downstreamGraphQLResponses")

  requestProducedErrors: _isNull(value: $__downstreamGraphQLResponses)
    @export(as: "requestProducedErrors")
    @export(as: "anyErrorProduced")
    @remove
}

query ExportGraphQLResponsesHaveErrors
  @depends(on: "SendGraphQLHTTPRequests")
  @skip(if: $isMissingPostInUpstream)
  @skip(if: $requestProducedErrors)
  @include(if: $hasDownstreamDomains)
{
  graphQLResponsesHaveErrors: _echo(value: $downstreamGraphQLResponses)    
    # Check if any GraphQL response has the "errors" entry
    @underEachArrayItem(
      passValueOnwardsAs: "response"
      affectDirectivesUnderPos: [1, 2]
    )
      @applyField(
        name: "_propertyIsSetInJSONObject"
        arguments: {
          object: $response
          by: {
            key: "errors"
          }
        }
        setResultInResponse: true
      )
    @export(as: "graphQLResponsesHaveErrors")
    @remove
}

query ValidateGraphQLResponsesHaveErrors
  @depends(on: "ExportGraphQLResponsesHaveErrors")
  @skip(if: $isMissingPostInUpstream)
  @skip(if: $requestProducedErrors)
  @include(if: $hasDownstreamDomains)
{
  anyGraphQLResponseHasErrors: _or(values: $graphQLResponsesHaveErrors)
    @export(as: "anyErrorProduced")
    @remove
}

query ExportRevertGraphQLHTTPRequestInputs(
  $username: String!
  $userPassword: String!
  $postSlug: String!
  $previousPostContent: String!
)
  @depends(on: "ValidateGraphQLResponsesHaveErrors")
  @include(if: $hasDownstreamDomains)
  @include(if: $anyErrorProduced)
{
  revertGraphQLHTTPRequestInputs: _echo(value: $downstreamGraphQLEndpoints)
    @underEachArrayItem(
      passValueOnwardsAs: "endpoint"
    )
      @applyField(
        name: "_echo",
        arguments: {
          value: {
            endpoint: $endpoint,
            query: $query,
            variables: [
              {
                name: "username",
                value: $username
              },
              {
                name: "userPassword",
                value: $userPassword
              },
              {
                name: "postSlug",
                value: $postSlug
              },
              {
                name: "postContent",
                value: $previousPostContent
              }
            ]
          }
        },
        setResultInResponse: true
      )
    @export(as: "revertGraphQLHTTPRequestInputs")
    @remove
}

query RevertGraphQLHTTPRequests
  @depends(on: "ExportRevertGraphQLHTTPRequestInputs")
  @skip(if: $isMissingPostInUpstream)
  @include(if: $hasDownstreamDomains)
  @include(if: $anyErrorProduced)
{
  revertGraphQLResponses: _sendGraphQLHTTPRequests(
    inputs: $sendGraphQLHTTPRequestInputs
  )
}

query ExecuteAll
  @depends(on: "RevertGraphQLHTTPRequests")
{
  id @remove
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

In the GraphQL query above, a post will not be distributed to any downstream site whenever its meta property `"downstream_domains"` is defined with an empty array as value.

This is possible because of the difference between function fields `_notNull` and `_notEmpty` (provided by the [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension):

- If meta property `"downstream_domains"` is not defined, its value is `null`, and both `_notNull` and `_notEmpty` eval to `false`
- If meta property `"downstream_domains"` is defined as an empty array, its value is `[]`, and only `_notEmpty` evals to `false`

</div>
