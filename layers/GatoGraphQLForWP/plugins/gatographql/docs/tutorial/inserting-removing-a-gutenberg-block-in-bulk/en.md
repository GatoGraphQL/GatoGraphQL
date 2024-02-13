# Inserting/Removing a (Gutenberg) block in bulk

We can update posts by modifying their (Gutenberg) block's HTML content.

Among other use cases, this is useful for promoting campaigns (such as when offering a discount during Black Friday):

- Before the campaign, we create a custom block `mycompany:black-friday-campaign-video` with our Call To Action, and execute a bulk operation to add it to all posts in the website
- After the campaign, we execute a bulk operation to remove the block from all posts

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For the GraphQL queries in this tutorial lesson to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

## Inserting a block in bulk

This GraphQL query identifies the 3rd paragraph block in a post (by searching for `<!-- /wp:paragraph -->`) and places the custom block's HTML content right after it:

```graphql
mutation InjectBlock(
  $limit: Int! = 5,
  $offset: Int! = 0
) {
  posts: posts(
    pagination: { limit: $limit, offset: $offset }
    sort: { by: ID, order: ASC }
  ) {
    id
    rawContent
    adaptedRawContent: _strRegexReplace(
      in: $__rawContent,
      searchRegex: "#(<!-- /wp:paragraph -->[\\s\\S]+<!-- /wp:paragraph -->[\\s\\S]+<!-- /wp:paragraph -->)#U",
      replaceWith: "${1}<!-- mycompany:black-friday-campaign-video -->\n<figure class=\"wp-block-video\"><video controls src=\"https://mysite.com/videos/BlackFriday2023.mp4\"></video></figure>\n<!-- /mycompany:black-friday-campaign-video -->",
      limit: 1
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
```

## Inserting the block with more options

This GraphQL query, similar to the previous one, generates the regex dynamically, allowing us to input the block type to search for, and after how many such blocks to place the custom block:

```graphql
query CreateRegex(
  $searchBlockType: String! = "wp:paragraph"
  $injectAfterBlockCount: Int!
  $injectBlockMarkup: String!
) {
  endingBlockMarkup: _sprintf(
    string: "<!-- /%s -->",
    values: [$searchBlockType]
  )
    @remove
  endingBlockMarkupArray: _arrayPad(
    array: [],
    length: $injectAfterBlockCount,
    value: $__endingBlockMarkup
  )
    @remove
  regexString: _arrayJoin(
    array: $__endingBlockMarkupArray,
    separator: "[\\s\\S]+"
  )
    @remove
  regex: _sprintf(
    string: "#(%s)#U",
    values: [$__regexString]
  )
    @export(as: "regex")
    @remove
  replaceWith: _sprintf(
    string: "${1}%s",
    values: [$injectBlockMarkup]
  )
    @export(as: "replaceWith")
    @remove
}

mutation InjectBlock(
  $limit: Int! = 5,
  $offset: Int! = 0
  $times: Int! = 1
)
  @depends(on: "CreateRegex")
{
  posts: posts(
    pagination: { limit: $limit, offset: $offset }
    sort: { by: ID, order: ASC }
  ) {
    id
    rawContent
    adaptedRawContent: _strRegexReplace(
      in: $__rawContent,
      searchRegex: $regex,
      replaceWith: $replaceWith,
      limit: $times
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
```

We provide the `variables` dictionary like this:

```json
{
  "injectBlockMarkup": "<!-- mycompany:black-friday-campaign-video -->\n<figure class=\"wp-block-video\"><video controls src=\"https://mysite.com/videos/BlackFriday2023.mp4\"></video></figure>\n<!-- /mycompany:black-friday-campaign-video -->",
  "injectAfterBlockCount": 3
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- During development/testing of the GraphQL query, print the regex patterns in the response by placing `#` before the `@remove` directives (as to comment them out):

```graphql
{
  field
    # @remove   <= Adding "#" before will disable the directive
}
```

</div>

## Removing a block in bulk

This GraphQL query searches for all posts containing the custom block, and removes it from their HTML source:

```graphql
mutation RemoveBlock {
  posts(filter: { search: "\"<!-- /mycompany:black-friday-campaign-video -->\"" } ) {
    id
    rawContent
    adaptedRawContent: _strRegexReplace(
      in: $__rawContent,
      searchRegex: "#(<!-- mycompany:black-friday-campaign-video -->[\\s\\S]+<!-- /mycompany:black-friday-campaign-video -->)#",
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
```

## Removing a block with more options

This GraphQL query, similar to the previous one, generates the regex dynamically, allowing us to input the block type to be removed:

```graphql
query CreateVars(
  $removeBlockType: String!
) {
  regex: _sprintf(
    string: "#(<!-- %1$s -->[\\s\\S]+<!-- /%1$s -->)#",
    values: [$removeBlockType]
  )
    @export(as: "regex")
    @remove

  search: _sprintf(
    string: "\"<!-- /%1$s -->\"",
    values: [$removeBlockType]
  )
    @export(as: "search")
    @remove
}

mutation RemoveBlock
  @depends(on: "CreateVars")
{
  posts(filter: { search: $search } ) {
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
```

We provide the `variables` dictionary like this:

```json
{
  "removeBlockType": "mycompany:black-friday-campaign-video"
}
```
