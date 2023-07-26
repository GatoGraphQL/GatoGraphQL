# Synchronizing content from upstream to downstream sites

Let's say that a media company has a network of WordPress sites for different regions, with every news article being published on a site or not only if it's suitable for that region.

For this situation, it makes sense to implement an architecture where:

- All content is published to (and edited in) a single upstream WordPress site, which acts as the single source of truth for content
- Suitable content is distributed to (but not edited in) each of the regional downstream WordPress sites

This recipe will demonstrate how to implement this architecture, with the upstream WordPress site needing to have the relevant Gato GraphQL extensions active, while the downstream sites need only have the free Gato GraphQL plugin.

## GraphQL query to synchronize content from upstream to downstream sites

The GraphQL query below (which is executed on the upstream WordPress site, triggered by the `post_updated` WordPress hook) does the following:

- It retrieves the content from the updated post
- It retrieves the meta property `"downstreams"`, which is an array containing the domains of all the downstream sites to distribute the content to; if this item is null, it synchronizes to all sites, provided by option `"downstreams"`
- It executes an `updatePost` mutation on each of the downstream sites
- If any downstream site produced an error, the mutation is reverted on all downstreams

```graphql

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
