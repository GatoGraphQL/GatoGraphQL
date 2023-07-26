# Synchronizing content from upstream to downstream sites

Let's say that a media company has a network of WordPress sites for different regions, with every news article being published on a site or not only if it's suitable for that region.

For this situation, it makes sense to implement an architecture where:

- All content is published to (and edited in) a single upstream WordPress site, which acts as the single source of truth for content
- Suitable content is distributed to (but not edited in) each of the regional downstream WordPress sites

This recipe will demonstrate how to implement this architecture, with the upstream WordPress site needing to have the relevant Gato GraphQL extensions active, while the downstream sites need only have the free Gato GraphQL plugin.

## GraphQL query to synchronize content from upstream to downstream sites

The GraphQL query below (which can be triggered by the `post_updated` WordPress hook) is executed on the upstream WordPress site, to synchronize the content of the updated post to the relevant downstream sites. (The query can be adapted to also synchronize the other properties -tags, categories, author and featured image-, as explained in the previous recipe.)

It does the following:

- It receives the slug of the updated post, and its new and previous content
- It retrieves the meta property `"downstreamDomains"`, which is an array containing the domains of the downstream sites which are suitable for that post
- If the meta property does not exist, it then retrieves option `"downstreamDomains"` from the `wp_options` table, which contains the list of all the downstream domains
- It executes an `updatePost` mutation on each of the downstream sites, passing the updated content
- If any downstream site produces an error, the mutation is reverted on all downstream sites

As in the previous recipe, we use the post slug as the common identifier across sites.

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  # initVariablesWithFalse: _echo(value: false)
  #   @export(as: "anyGraphQLResponseHasErrors")
  #   @remove
}

query GetCustomDownstreamDomains($postSlug: String!)
  @depends(on: "InitializeDynamicVariables")
{
  post(by: { slug: $postSlug }) {
    customDownstreamDomains: metaValues(key: "downstreamDomains")
      @export(as: "downstreamDomains")

    hasCustomDownstreamDomains: _notEmpty(value: $__customDownstreamDomains)
      @export(as: "hasCustomDownstreamDomains")
      @remove
  }
}

query GetAllDownstreamDomains
  @depends(on: "GetCustomDownstreamDomains")
  @skip(if: $hasCustomDownstreamDomains)
{
  allDownstreamDomains: optionValues(name: "downstreamDomains")
    @default(value: [])
    @export(as: "downstreamDomains")
}

############################################################
# Attach "/graphql" to the domain, to point to that site's
# GraphQL single endpoint
############################################################
query ExportDownstreamGraphQLEndpoints
  @depends(on: "GetAllDownstreamDomains")
{
  downstreamGraphQLEndpoints: _echo(value: $downstreamDomains)
    @underEachArrayItem(
      passValueOnwardsAs: "domain"
    )
      @strAppend(string: "/graphql")
    @export(as: "downstreamGraphQLEndpoints")
}

query ExportSendGraphQLHTTPRequestInputs(
  $postSlug: String!
  $newPostContent: String!
)
  @depends(on: "ExportDownstreamGraphQLEndpoints")
{
  query: _echo(value: """
    
query UpdatePost(
  $postSlug: String!
  $postContent: String!
) {
  updatePost(input: {
    id: $postId,
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

    """
  )
    @export(as: "query")

  sendGraphQLHTTPRequestInputs: _echo(value: $downstreamGraphQLEndpoints)
    @underEachArrayItem(
      passValueOnwardsAs: "endpoint"
    )
      @applyField(
        name: "_echo",
        arguments: {
          value: {
            endpoint: $endpoint,
            query: $__query,
            variables: [
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
  @skip(if: $requestProducedErrors)
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
  @skip(if: $requestProducedErrors)
{
  anyGraphQLResponseHasErrors: _or(values: $graphQLResponsesHaveErrors)
    @export(as: "anyErrorProduced")
    @remove
}

query ExportRevertGraphQLHTTPRequestInputs(
  $postSlug: String!
  $previousPostContent: String!
)
  @depends(on: "ValidateGraphQLResponsesHaveErrors")
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
            query: $__query,
            variables: [
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
{
  revertGraphQLResponses: _sendGraphQLHTTPRequests(
    inputs: $sendGraphQLHTTPRequestInputs
  )
}
```

## Triggering the synchronization via a hook



## Adding a transactional state across all downstream sites

We can also implement a transactional state across all downstream sites, to make sure that they do not get out of sync with each other. To achieve this, , when synchronizing content, if any downstream site fails (for instance, because the server is down), then the mutation is reverted on all other downstream sites.

## Reverting mutations in case of error

Say can also do:

"Testing mutations and restoring the original value"

Remove block by type:

```graphql
query CreateVars {
  foundPosts: posts(filter: { search: "\"<!-- /wp:columns -->\"" } ) {
    id @export(as: "postIDs", type: LIST)
    rawContent
    originalInputs: _echo(value: {
      id: $__id,
      contentAs: { html: $__rawContent }
    }) @export(as: "originalInputs")
  }
}

mutation RemoveBlock
  @depends(on: "CreateVars")
{
  posts(filter: { ids: $postIDs } ) {
    id
    rawContent
    adaptedRawContent: _strRegexReplace(
      in: $__rawContent,
      searchRegex: "#(<!-- wp:columns -->[\\s\\S]+<!-- /wp:columns -->)#",
      replaceWith: ""
    )
    update(input: {
      contentAs: { html: $__adaptedRawContent },
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        id
        rawContent
      }
    }
  }
}

mutation RestorePosts
  @depends(on: "CreateVars")
{
  restorePosts: _echo(value: $originalInputs)
    @underEachArrayItem(passValueOnwardsAs: "input")
      @applyField(
        name: "updatePost"
        arguments: { input: $input }
      )
}

query CheckRestoredPosts
  @depends(on: "CreateVars")
{
  restoredPosts: posts(filter: { ids: $postIDs } ) {
    id
    rawContent
  }
}

query RemoveBlockAndThenRestorePosts
  @depends(on: ["RemoveBlock", "RestorePosts", "CheckRestoredPosts"])
{
  id
}
```

Detailed:

```graphql
query CreateVars(
  $removeBlockType: String!
) {
  regex: _sprintf(
    string: "#(<!-- %1$s -->[\\s\\S]+<!-- /%1$s -->)#",
    values: [$removeBlockType]
  ) @export(as: "regex")

  search: _sprintf(
    string: "\"<!-- /%1$s -->\"",
    values: [$removeBlockType]
  )
  
  foundPosts: posts(filter: { search: $__search } ) {
    id @export(as: "postIDs", type: LIST)
    rawContent
    originalInputs: _echo(value: {
      id: $__id,
      contentAs: { html: $__rawContent }
    }) @export(as: "originalInputs")
  }
}

mutation RemoveBlock
  @depends(on: "CreateVars")
{
  posts(filter: { ids: $postIDs } ) {
    id
    rawContent
    adaptedRawContent: _strRegexReplace(
      in: $__rawContent,
      searchRegex: $regex,
      replaceWith: ""
    )
    update(input: {
      contentAs: { html: $__adaptedRawContent },
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        id
        rawContent
      }
    }
  }
}

mutation RestorePosts
  @depends(on: "CreateVars")
{
  restorePosts: _echo(value: $originalInputs)
    @underEachArrayItem(passValueOnwardsAs: "input")
      @applyField(
        name: "updatePost"
        arguments: { input: $input }
      )
}

query CheckRestoredPosts
  @depends(on: "CreateVars")
{
  restoredPosts: posts(filter: { ids: $postIDs } ) {
    id
    rawContent
  }
}

query RemoveBlockAndThenRestorePosts
  @depends(on: ["RemoveBlock", "RestorePosts", "CheckRestoredPosts"])
{
  id
}
```

and vars:

```json
{
  "removeBlockType": "wp:columns"
}
```
