# Extracting the image URLs from all Image blocks in a post

We can iterate the inner structure of (Gutenberg) blocks in the post content, and extract those desired items.

This recipe demonstrates how to extract the image URLs from all `core/image` blocks in a post.

## GraphQL query to extract the image URLs from all `core/image` blocks in a post

```graphql
query InitializeEmptyVariables {
  emptyArray: _echo(value: [])
    @export(as: "coreImageAltItems")
    @export(as: "coreImageAltReplacementsFrom")
    @export(as: "coreImageAltReplacementsTo")
}

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
            by: { key: "alt" }
            failIfNonExistingKeyOrPath: false
          )
            @export(
              as: "coreImageAltItems"
            )
  }
}

query TransformData
  @depends(on: "FetchData")
{  
  transformations: _echo(value: {
    coreImageAlt: {
      from: $coreImageAltItems,
      to: $coreImageAltItems,
    },
  })
    @underEachJSONObjectProperty
      @underJSONObjectProperty(by: { key: "to" })
        @underEachArrayItem
          @strUpperCase
    @export(as: "transformations")
}

query EscapeRegexStrings
  @depends(on: "TransformData")
{  
  espacedRegexStrings: _echo(value: $transformations)
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

query CreateRegexReplacements
  @depends(on: "EscapeRegexStrings")
{  
  regexReplacements: _echo(value: $escapedRegexTransformations)
    @underJSONObjectProperty(
      by: { key: "coreImageAlt" }
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
              string: "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")%s(\\\".*>.*\\n?<!-- /wp:image -->)#",
              values: [$value]
            },
            setResultInResponse: true
          )
        @export(
          as: "coreImageAltReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreImageAltReplacementsTo",
        )
}

query ExecuteRegexReplacements
  @depends(on: "CreateRegexReplacements")
{  
  transformedContentSource: _echo(value: $contentSource)
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreImageAltReplacementsFrom,
      replaceWith: $coreImageAltReplacementsTo
    )
    
    @export(as: "transformedContentSource")
}

mutation TranslatePost($postID: ID!)
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
