# Modifying (and storing again) the image URLs from all Image blocks in a post

In the previous recipe, we learnt that we can iterate the inner structure of (Gutenberg) blocks in the post content, and extract desired items.

Let's now explore how to transform those items, and store them again in the post.

This recipe modifies the URL of images in the `core/image` blocks in a post:

- Replacing `mysite.com` to `cdn.mysite.com` (as to start serving those assets from a CDN)
- Replacing `.jpg` with `.avif`

## GraphQL query to transform (and store again) the image URLs from all `core/image` blocks in a post

Mutation `updatePost` receives the post's HTML content. Then, we must:

- Retrieve the post's `sourceContent`
- Apply transformations, replacing the original URLs with the converted URLs
- Store the adapted content

The transformations will be executed via regex search and replace, with each regex pattern generated dynamically based on the inner HTML content of the block (in this case, the `src` element in the `core/image` block's HTML code). As such, we must escape characters in both the regex pattern and the replacement string:

- The generated regex patterns could contain any of the regex special characters (such as `.`, `+`, `(`, etc), so these must be escaped
- The replacements could contain a regex replacement variable (such as `$1`), so these must be escaped

```graphql
query InitializeEmptyVariables {
  emptyArray: _echo(value: [])
    @export(as: "coreImageURLItems")
    @export(as: "coreImageURLReplacementsFrom")
    @export(as: "coreImageURLReplacementsTo")
}

# Extract all the image URLs from the `core/image` blocks, and export them under `$coreImageURLItems`
query FetchData($postID: ID!)
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
  @depends(on: "InitializeEmptyVariables")
{
  post(by: { id: $postID } ) {
    contentSource
      @export(as: "contentSource")

    coreImage: blockFlattenedDataItems(
      filterBy: { include: "core/image" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { key: "attributes" }
        )
          @underJSONObjectProperty(
            by: { key: "url" }
            failIfNonExistingKeyOrPath: false
          )
            @export(
              as: "coreImageURLItems"
            )
  }
}

# Convert the URLs and place the results under `$transformations`
query TransformData
  @depends(on: "FetchData")
{  
  transformations: _echo(value: {
    coreImageURL: {
      from: $coreImageURLItems,
      to: $coreImageURLItems,
    },
  })
    @underEachJSONObjectProperty
      @underJSONObjectProperty(by: { key: "to" })
        @underEachArrayItem
          @strRegexReplace(
            searchRegex: "#^https?://mysite.com/(.*)\\.jpg$#",
            replaceWith: "https://cdn.mysite.com/$1.avif"
        )
    @export(as: "transformations")
}

# Escape the regex patterns and their replacements
query EscapeRegexStrings
  @depends(on: "TransformData")
{  
  escapedRegexStrings: _echo(value: $transformations)
    @underEachJSONObjectProperty
      @underJSONObjectProperty(by: { key: "from" })
        @underEachArrayItem
          @strReplaceMultiple(
            search: [
              "\\",
              "^",
              "$",
              "|",
              "[",
              "]",
              "(",
              ")",
              "{",
              "{",
              "#",
              "?",
              ".",
              "*",
              "+"
            ],
            replaceWith: [
              "\\\\",
              "\\^",
              "\\$",
              "\\|",
              "\\[",
              "\\]",
              "\\(",
              "\\)",
              "\\{",
              "\\}",
              "\\#",
              "\\?",
              "\\.",
              "\\*",
              "\\+"
            ]
          )
    @underEachJSONObjectProperty
      @underJSONObjectProperty(
        by: { key: "to" }
        affectDirectivesUnderPos: [1, 3],
      )
        @underEachArrayItem
          @strRegexReplace(
            searchRegex: "#\\$(\\d+)#",
            replaceWith: "\\\\\\$1"
          )
        @underEachArrayItem(
          passValueOnwardsAs: "value"
        )
          @applyField(
            name: "_sprintf",
            arguments: {
              string: "$1%s$2",
              values: [$value]
            },
            setResultInResponse: true
          )
    @export(as: "escapedRegexTransformations")
}

# Generate the regex patterns, and assign them to `$coreImageURLReplacementsFrom`
query CreateRegexReplacements
  @depends(on: "EscapeRegexStrings")
{  
  regexReplacements: _echo(value: $escapedRegexTransformations)
    @underJSONObjectProperty(
      by: { key: "coreImageURL" }
      affectDirectivesUnderPos: [1, 5]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 3],
      )
        @underEachArrayItem(
          passValueOnwardsAs: "value"
        )
          @applyField(
            name: "_sprintf",
            arguments: {
              string: "#(<!-- wp:image .*?-->\\n?.*<img .*?src=\\\")%s(\\\".*>.*\\n?<!-- /wp:image -->)#",
              values: [$value]
            },
            setResultInResponse: true
          )
        @export(
          as: "coreImageURLReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreImageURLReplacementsTo",
        )
}

# Execute the regex search and replace, export the results under `$transformedContentSource`
query ExecuteRegexReplacements
  @depends(on: "CreateRegexReplacements")
{  
  transformedContentSource: _echo(value: $contentSource)
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreImageURLReplacementsFrom,
      replaceWith: $coreImageURLReplacementsTo
    )
    
    @export(as: "transformedContentSource")
}

# Execute the mutation to update the post
mutation ModifyAndUpdatePost($postID: ID!)
  @depends(on: "ExecuteRegexReplacements")
{
  updatePost(input: {
    id: $postID,
    contentAs: {
      html: $transformedContentSource
    }
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
      title
      contentSource
    }    
  }
}
```
