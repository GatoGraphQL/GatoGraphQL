# Lesson 12: Bulk translating block content in multiple posts to a different language

The previous tutorial lesson demonstrated how to translate a post. This lesson is the equivalent but translating multiple posts at once (in bulk), while executing a single call to the Google Translate API containing all text to translate from all the posts.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Translating multiple posts in a single GraphQL request provides better results than executing multiple GraphQL requests translating one post each.

That's because we are able to provide all text to translate, for all fields and for all posts, to a single `@strTranslate` execution (as demonstrated in the GraphQL query below). As Google Translate will then receive more data, enhancing its context, it will be able to provide more accurate translations.

</div>

## GraphQL query to translate block content in multiple posts

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

This GraphQL query is similar to the one from the previous tutorial lesson, but receiving the data for multiple posts as inputs, storing these data separately, executing the translation for all posts all at once, and finally iterating over all the posts, retrieving that post's translations and updating the post.

In order to keep the regex patterns and translations for the posts separate, we use `@export(type: DICTIONARY)` when exporting data via dynamic variables, which keeps the data organized under the corresponding post ID.

```graphql
query InitializeEmptyVariables($postIDs: ID!) {
  emptyVars: posts(filter: { ids: $postIDs } ) {
    emptyArray: _echo(value: [])
      @export(
        as: "coreHeadingContentItems"
        type: DICTIONARY
      )
      @export(
        as: "coreHeadingContentReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreHeadingContentReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreParagraphContentItems"
        type: DICTIONARY
      )
      @export(
        as: "coreParagraphContentReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreParagraphContentReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreImageAltItems"
        type: DICTIONARY
      )
      @export(
        as: "coreImageAltReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreImageAltReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreImageCaptionItems"
        type: DICTIONARY
      )
      @export(
        as: "coreImageCaptionReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreImageCaptionReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreButtonTextItems"
        type: DICTIONARY
      )
      @export(
        as: "coreButtonTextReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreButtonTextReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreTableCaptionItems"
        type: DICTIONARY
      )
      @export(
        as: "coreTableCaptionReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreTableCaptionReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreTableBodyCellsContentItems"
        type: DICTIONARY
      )
      @export(
        as: "coreTableBodyCellsContentReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreTableBodyCellsContentReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreListItemContentItems"
        type: DICTIONARY
      )
      @export(
        as: "coreListItemContentReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreListItemContentReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreCoverAltItems"
        type: DICTIONARY
      )
      @export(
        as: "coreCoverAltReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreCoverAltReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreMediaTextAltItems"
        type: DICTIONARY
      )
      @export(
        as: "coreMediaTextAltReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreMediaTextAltReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreVerseContentItems"
        type: DICTIONARY
      )
      @export(
        as: "coreVerseContentReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreVerseContentReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreQuoteCitationItems"
        type: DICTIONARY
      )
      @export(
        as: "coreQuoteCitationReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreQuoteCitationReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "corePullquoteCitationItems"
        type: DICTIONARY
      )
      @export(
        as: "corePullquoteCitationReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "corePullquoteCitationReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "corePullquoteValueItems"
        type: DICTIONARY
      )
      @export(
        as: "corePullquoteValueReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "corePullquoteValueReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreAudioCaptionItems"
        type: DICTIONARY
      )
      @export(
        as: "coreAudioCaptionReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreAudioCaptionReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreVideoCaptionItems"
        type: DICTIONARY
      )
      @export(
        as: "coreVideoCaptionReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreVideoCaptionReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "corePreformattedContentItems"
        type: DICTIONARY
      )
      @export(
        as: "corePreformattedContentReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "corePreformattedContentReplacementsTo"
        type: DICTIONARY
      )

      @export(
        as: "coreEmbedCaptionItems"
        type: DICTIONARY
      )
      @export(
        as: "coreEmbedCaptionReplacementsFrom"
        type: DICTIONARY
      )
      @export(
        as: "coreEmbedCaptionReplacementsTo"
        type: DICTIONARY
      )
  }
}

query FetchData($postIDs: ID!)
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
  @depends(on: "InitializeEmptyVariables")
{
  posts(filter: { ids: $postIDs } ) {
    id
    title
      @export(as: "title", type: DICTIONARY)
    rawContent
      @export(as: "rawContent", type: DICTIONARY)
    

    coreHeading: blockFlattenedDataItems(
      filterBy: { include: "core/heading" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.content" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreHeadingContentItems"
            type: DICTIONARY
          )
    

    coreParagraph: blockFlattenedDataItems(
      filterBy: { include: "core/paragraph" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.content" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreParagraphContentItems"
            type: DICTIONARY
          )
    

    coreImage: blockFlattenedDataItems(
      filterBy: { include: "core/image" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { key: "attributes" }
          affectDirectivesUnderPos: [1, 3]
        )
          @underJSONObjectProperty(
            by: { key: "alt" }
            failIfNonExistingKeyOrPath: false
          )
            @export(
              as: "coreImageAltItems"
              type: DICTIONARY
            )
    
          @underJSONObjectProperty(
            by: { key: "caption" }
            failIfNonExistingKeyOrPath: false
          )
            @export(
              as: "coreImageCaptionItems"
              type: DICTIONARY
            )

    
    coreButton: blockFlattenedDataItems(
      filterBy: { include: "core/button" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.text" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreButtonTextItems"
            type: DICTIONARY
          )
    

    coreTable: blockFlattenedDataItems(
      filterBy: { include: "core/table" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { key: "attributes" }
          affectDirectivesUnderPos: [1, 3]
        )
          @underJSONObjectProperty(
            by: { key: "caption" }
            failIfNonExistingKeyOrPath: false
          )
            @export(
              as: "coreTableCaptionItems"
              type: DICTIONARY
            )
    
          @underJSONObjectProperty(
            by: { key: "body" }
            failIfNonExistingKeyOrPath: false
          )
            @underEachArrayItem
              @underJSONObjectProperty(
                by: { key: "cells" }
              )
                @underEachArrayItem
                  @underJSONObjectProperty(
                    by: { key: "content" }
                  )
                    @export(
                      as: "coreTableBodyCellsContentItems"
                      type: DICTIONARY
                    )

    
    coreListItem: blockFlattenedDataItems(
      filterBy: { include: "core/list-item" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.content" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreListItemContentItems"
            type: DICTIONARY
          )
    

    coreCover: blockFlattenedDataItems(
      filterBy: { include: "core/cover" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.alt" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreCoverAltItems"
            type: DICTIONARY
          )
    

    coreMediaText: blockFlattenedDataItems(
      filterBy: { include: "core/media-text" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.mediaAlt" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreMediaTextAltItems"
            type: DICTIONARY
          )
    

    coreVerse: blockFlattenedDataItems(
      filterBy: { include: "core/verse" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.content" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreVerseContentItems"
            type: DICTIONARY
          )
    

    coreQuote: blockFlattenedDataItems(
      filterBy: { include: "core/quote" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.citation" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreQuoteCitationItems"
            type: DICTIONARY
          )
    

    corePullquote: blockFlattenedDataItems(
      filterBy: { include: "core/pullquote" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { key: "attributes" }
          affectDirectivesUnderPos: [1, 3]
        )
          @underJSONObjectProperty(
            by: { key: "citation" }
            failIfNonExistingKeyOrPath: false
          )
            @export(
              as: "corePullquoteCitationItems"
              type: DICTIONARY
            )
    
          @underJSONObjectProperty(
            by: { key: "value" }
            failIfNonExistingKeyOrPath: false
          )
            @export(
              as: "corePullquoteValueItems"
              type: DICTIONARY
            )
    

    coreAudio: blockFlattenedDataItems(
      filterBy: { include: "core/audio" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.caption" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreAudioCaptionItems"
            type: DICTIONARY
          )
    

    coreVideo: blockFlattenedDataItems(
      filterBy: { include: "core/video" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.caption" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreVideoCaptionItems"
            type: DICTIONARY
          )
    

    corePreformatted: blockFlattenedDataItems(
      filterBy: { include: "core/preformatted" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.content" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "corePreformattedContentItems"
            type: DICTIONARY
          )
    

    coreEmbed: blockFlattenedDataItems(
      filterBy: { include: "core/embed" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.caption" }
          failIfNonExistingKeyOrPath: false
        )
          @export(
            as: "coreEmbedCaptionItems"
            type: DICTIONARY
          )
  }
}

query AdaptData
  @depends(on: "FetchData")
{
  adaptedToTitle: _echo(value: $title)
    @underEachJSONObjectProperty(
      passValueOnwardsAs: "value"
    )
      @applyField(
        name: "_echo"
        arguments: {
          value: [$value]
        }
        setResultInResponse: true
      )
    @export(as: "adaptedToTitle")
  adaptedFromTitle: _echo(value: $title)
    @underEachJSONObjectProperty
      @applyField(
        name: "_echo"
        arguments: {
          value: [""]
        }
        setResultInResponse: true
      )
    @export(as: "adaptedFromTitle")
}

query TransformData(
  $translateToLang: String!
)
  @depends(on: "AdaptData")
{
  transformations: _echo(value: {
    meta: {
      from: $adaptedFromTitle,
      to: $adaptedToTitle,
    }
    coreHeadingContent: {
      from: $coreHeadingContentItems,
      to: $coreHeadingContentItems,
    },
    coreParagraphContent: {
      from: $coreParagraphContentItems,
      to: $coreParagraphContentItems,
    },
    coreImageAlt: {
      from: $coreImageAltItems,
      to: $coreImageAltItems,
    },
    coreImageCaption: {
      from: $coreImageCaptionItems,
      to: $coreImageCaptionItems,
    },
    coreButtonText: {
      from: $coreButtonTextItems
      to: $coreButtonTextItems
    },
    coreTableCaption: {
      from: $coreTableCaptionItems,
      to: $coreTableCaptionItems,
    },
    coreTableBodyCellsContent: {
      from: $coreTableBodyCellsContentItems,
      to: $coreTableBodyCellsContentItems,
    },
    coreListItemContent: {
      from: $coreListItemContentItems,
      to: $coreListItemContentItems,
    },
    coreCoverAlt: {
      from: $coreCoverAltItems,
      to: $coreCoverAltItems,
    },
    coreMediaTextAlt: {
      from: $coreMediaTextAltItems,
      to: $coreMediaTextAltItems,
    },
    coreVerseContent: {
      from: $coreVerseContentItems,
      to: $coreVerseContentItems,
    },
    coreQuoteCitation: {
      from: $coreQuoteCitationItems,
      to: $coreQuoteCitationItems,
    },
    corePullquoteCitation: {
      from: $corePullquoteCitationItems,
      to: $corePullquoteCitationItems,
    },
    corePullquoteValue: {
      from: $corePullquoteValueItems,
      to: $corePullquoteValueItems,
    },
    coreAudioCaption: {
      from: $coreAudioCaptionItems,
      to: $coreAudioCaptionItems,
    },
    coreVideoCaption: {
      from: $coreVideoCaptionItems,
      to: $coreVideoCaptionItems,
    },
    corePreformattedContent: {
      from: $corePreformattedContentItems,
      to: $corePreformattedContentItems,
    },
    coreEmbedCaption: {
      from: $coreEmbedCaptionItems,
      to: $coreEmbedCaptionItems,
    },
  })
    @underEachJSONObjectProperty
      @underJSONObjectProperty(by: { key: "to" })
        @underEachJSONObjectProperty
          @underEachArrayItem
            @strTranslate(to: $translateToLang)
    @export(as: "transformations")
}

query EscapeRegexStrings
  @depends(on: "TransformData")
{  
  escapedRegexStrings: _echo(value: $transformations)
    @underEachJSONObjectProperty(
      filter: {
        by: {
          excludeKeys: "meta"
        }
      }
    )
      @underJSONObjectProperty(by: { key: "from" })
        @underEachJSONObjectProperty
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
    @underEachJSONObjectProperty(
      filter: {
        by: {
          excludeKeys: "meta"
        }
      }
    )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @underEachJSONObjectProperty(
          affectDirectivesUnderPos: [1, 3]
        )
          @underEachArrayItem
            @strRegexReplace(
              searchRegex: "#\\$(\\d+)#",
              replaceWith: "\\\\\\${1}"
            )
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "${1}%s${2}",
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
      by: { key: "coreHeadingContent" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)%s(</h[1-6]>\\n?<!-- /wp:heading -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreHeadingContentReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreHeadingContentReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "coreParagraphContent" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)%s(</p>\\n?<!-- /wp:paragraph -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreParagraphContentReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreParagraphContentReplacementsTo",
        )
  
  
    @underJSONObjectProperty(
      by: { key: "coreImageAlt" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
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
  
  
    @underJSONObjectProperty(
      by: { key: "coreImageCaption" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:image .*?-->\\n?.*<figcaption ?.*?>)%s(</figcaption>.*\\n?<!-- /wp:image -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreImageCaptionReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreImageCaptionReplacementsTo",
        )
  
  
    @underJSONObjectProperty(
      by: { key: "coreButtonText" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:button .*?-->\\n?.*<a ?.*?>)%s(</a>.*\\n?<!-- /wp:button -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreButtonTextReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreButtonTextReplacementsTo",
        )
  
  
    @underJSONObjectProperty(
      by: { key: "coreTableCaption" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:table .*?-->\\n?.*<figcaption ?.*?>.*)%s(.*</figcaption>.*\\n?<!-- /wp:table -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreTableCaptionReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreTableCaptionReplacementsTo",
        )
  
  
    @underJSONObjectProperty(
      by: { key: "coreTableBodyCellsContent" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)%s(.*</table>.*\\n?<!-- /wp:table -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreTableBodyCellsContentReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreTableBodyCellsContentReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "coreListItemContent" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)%s(</li>\\n?<!-- /wp:list-item -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreListItemContentReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreListItemContentReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "coreCoverAlt" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:cover .*?-->\\n?.*<img .*?alt=\\\")%s(\\\".*>.*\\n?<!-- /wp:cover -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreCoverAltReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreCoverAltReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "coreMediaTextAlt" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:media-text .*?-->\\n?<div .*><figure .*><img .*?alt=\\\")%s(\\\")#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreMediaTextAltReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreMediaTextAltReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "coreVerseContent" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:verse .*?-->\\n?<pre ?.*?>)%s(</pre>\\n?<!-- /wp:verse -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreVerseContentReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreVerseContentReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "coreQuoteCitation" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:quote .*?-->\\n?<blockquote ?.*?>.*<cite ?.*?>)%s(</cite></blockquote>\\n?<!-- /wp:quote -->)#s",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreQuoteCitationReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreQuoteCitationReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "corePullquoteCitation" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:pullquote .*?-->\\n?<figure ?.*?><blockquote ?.*?><p ?.*?>.*</p><cite ?.*?>)%s(</cite></blockquote></figure>\\n?<!-- /wp:pullquote -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "corePullquoteCitationReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "corePullquoteCitationReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "corePullquoteValue" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:pullquote .*?-->\\n?<figure ?.*?><blockquote ?.*?><p ?.*?>)%s(</p><cite ?.*?>.*</cite></blockquote></figure>\\n?<!-- /wp:pullquote -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "corePullquoteValueReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "corePullquoteValueReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "coreAudioCaption" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:audio .*?-->\\n?<figure ?.*?><audio ?.*?>.*</audio><figcaption ?.*?>)%s(</figcaption></figure>\\n?<!-- /wp:audio -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreAudioCaptionReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreAudioCaptionReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "coreVideoCaption" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:video .*?-->\\n?<figure ?.*?><video ?.*?>.*</video><figcaption ?.*?>)%s(</figcaption></figure>\\n?<!-- /wp:video -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreVideoCaptionReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreVideoCaptionReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "corePreformattedContent" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:preformatted .*?-->\\n?<pre ?.*?>)%s(</pre>\\n?<!-- /wp:preformatted -->)#",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "corePreformattedContentReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "corePreformattedContentReplacementsTo",
        )


    @underJSONObjectProperty(
      by: { key: "coreEmbedCaption" }
      affectDirectivesUnderPos: [1, 6]
    )
      @underJSONObjectProperty(
        by: { key: "from" }
        affectDirectivesUnderPos: [1, 4],
      )
        @underEachJSONObjectProperty
          @underEachArrayItem(
            passValueOnwardsAs: "value"
          )
            @applyField(
              name: "_sprintf",
              arguments: {
                string: "#(<!-- wp:embed .*?-->\\n?<figure ?.*?><div ?.*?>.*</div><figcaption ?.*?>)%s(</figcaption></figure>\\n?<!-- /wp:embed -->)#s",
                values: [$value]
              },
              setResultInResponse: true
            )
        @export(
          as: "coreEmbedCaptionReplacementsFrom",
        )
      @underJSONObjectProperty(
        by: { key: "to" }
      )
        @export(
          as: "coreEmbedCaptionReplacementsTo",
        )
}

query ExecuteRegexReplacements
  @depends(on: "CreateRegexReplacements")
{
  transformedRawContent: _echo(value: $rawContent)
    @underEachJSONObjectProperty(
      passKeyOnwardsAs: "postID"
      affectDirectivesUnderPos: [
        1, 2,
        6, 7,
        11, 12,
        16, 17,
        21, 22,
        26, 27,
        31, 32,
        36, 37,
        41, 42,
        46, 47,
        51, 52,
        56, 57,
        61, 62,
        66, 67,
        71, 72,
        76, 77,
        81, 82,
        86, 87,
      ]
    )
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreHeadingContentReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreHeadingContentReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreHeadingContentReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreHeadingContentReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreHeadingContentReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreHeadingContentReplacementsFrom,
          replaceWith: $postCoreHeadingContentReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreParagraphContentReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreParagraphContentReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreParagraphContentReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreParagraphContentReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreParagraphContentReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreParagraphContentReplacementsFrom,
          replaceWith: $postCoreParagraphContentReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreImageAltReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreImageAltReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreImageAltReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreImageAltReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreImageAltReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreImageAltReplacementsFrom,
          replaceWith: $postCoreImageAltReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreImageCaptionReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreImageCaptionReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreImageCaptionReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreImageCaptionReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreImageCaptionReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreImageCaptionReplacementsFrom,
          replaceWith: $postCoreImageCaptionReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreButtonTextReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreButtonTextReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreButtonTextReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreButtonTextReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreButtonTextReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreButtonTextReplacementsFrom,
          replaceWith: $postCoreButtonTextReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreTableCaptionReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreTableCaptionReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreTableCaptionReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreTableCaptionReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreTableCaptionReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreTableCaptionReplacementsFrom,
          replaceWith: $postCoreTableCaptionReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreTableBodyCellsContentReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreTableBodyCellsContentReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreTableBodyCellsContentReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreTableBodyCellsContentReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreTableBodyCellsContentReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreTableBodyCellsContentReplacementsFrom,
          replaceWith: $postCoreTableBodyCellsContentReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreListItemContentReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreListItemContentReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreListItemContentReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreListItemContentReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreListItemContentReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreListItemContentReplacementsFrom,
          replaceWith: $postCoreListItemContentReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreCoverAltReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreCoverAltReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreCoverAltReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreCoverAltReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreCoverAltReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreCoverAltReplacementsFrom,
          replaceWith: $postCoreCoverAltReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreMediaTextAltReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreMediaTextAltReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreMediaTextAltReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreMediaTextAltReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreMediaTextAltReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreMediaTextAltReplacementsFrom,
          replaceWith: $postCoreMediaTextAltReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreVerseContentReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreVerseContentReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreVerseContentReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreVerseContentReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreVerseContentReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreVerseContentReplacementsFrom,
          replaceWith: $postCoreVerseContentReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreQuoteCitationReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreQuoteCitationReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreQuoteCitationReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreQuoteCitationReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreQuoteCitationReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreQuoteCitationReplacementsFrom,
          replaceWith: $postCoreQuoteCitationReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $corePullquoteCitationReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $corePullquoteCitationReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCorePullquoteCitationReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $corePullquoteCitationReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCorePullquoteCitationReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCorePullquoteCitationReplacementsFrom,
          replaceWith: $postCorePullquoteCitationReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $corePullquoteValueReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $corePullquoteValueReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCorePullquoteValueReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $corePullquoteValueReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCorePullquoteValueReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCorePullquoteValueReplacementsFrom,
          replaceWith: $postCorePullquoteValueReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreAudioCaptionReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreAudioCaptionReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreAudioCaptionReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreAudioCaptionReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreAudioCaptionReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreAudioCaptionReplacementsFrom,
          replaceWith: $postCoreAudioCaptionReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreVideoCaptionReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreVideoCaptionReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreVideoCaptionReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreVideoCaptionReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreVideoCaptionReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreVideoCaptionReplacementsFrom,
          replaceWith: $postCoreVideoCaptionReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $corePreformattedContentReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $corePreformattedContentReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCorePreformattedContentReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $corePreformattedContentReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCorePreformattedContentReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCorePreformattedContentReplacementsFrom,
          replaceWith: $postCorePreformattedContentReplacementsTo
        )
    
    
      @applyField(
        name: "_propertyExistsInJSONObject"
        arguments: {
          object: $coreEmbedCaptionReplacementsFrom
          by: { key: $postID }
        }
        passOnwardsAs: "hasPostID"
      )
      @if(
        condition: $hasPostID
        affectDirectivesUnderPos: [1, 2, 3]
      )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreEmbedCaptionReplacementsFrom,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreEmbedCaptionReplacementsFrom"
        )
        @applyField(
          name: "_objectProperty",
          arguments: {
            object: $coreEmbedCaptionReplacementsTo,
            by: {
              key: $postID
            }
          },
          passOnwardsAs: "postCoreEmbedCaptionReplacementsTo"
        )
        @strRegexReplaceMultiple(
          limit: 1,
          searchRegex: $postCoreEmbedCaptionReplacementsFrom,
          replaceWith: $postCoreEmbedCaptionReplacementsTo
        )
    
    @export(as: "transformedRawContent")
}

query PrepareMetaReplacements
  @depends(on: "TransformData")
{  
  transformedMeta: _echo(value: $title)
    @underEachJSONObjectProperty(
      passKeyOnwardsAs: "postID"
      affectDirectivesUnderPos: [1, 2, 3]
    )
      @applyField(
        name: "_sprintf",
        arguments: {
          string: "meta.to.%s",
          values: [$postID]
        }
        passOnwardsAs: "titlePath"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $transformations
          by: { path: $titlePath }
        }
        passOnwardsAs: "transformedPostTitleAsArray"
      )
      @applyField(
        name: "_arrayItem",
        arguments: {
          array: $transformedPostTitleAsArray
          position: 0
        }
        setResultInResponse: true
      )
    @export(
      as: "transformedTitle"
    )
}

mutation TranslatePosts($postIDs: ID!)
  @depends(on: [
    "ExecuteRegexReplacements",
    "PrepareMetaReplacements"
]) {
  updatePosts: posts(filter: { ids: $postIDs } ) {
    id
    transformedRawContent: _objectProperty(
      object: $transformedRawContent,
      by: {
        key: $__id
      }
    )
    transformedTitle: _objectProperty(
      object: $transformedTitle,
      by: {
        key: $__id
      }
    )
    update(input: {
      title: $__transformedTitle,
      contentAs: {
        html: $__transformedRawContent
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
        rawContent
      }
    }
  }
}
```

Passing these `variables`:

```json
{
  "postIDs": [40, 19],
  "translateToLang": "es"
}
```

...produces this response:

```json
{
  "data": {
    "emptyVars": [
      {
        "emptyArray": []
      },
      {
        "emptyArray": []
      }
    ],
    "posts": [
      {
        "id": 40,
        "title": "Welcome to a single post full of blocks!",
        "rawContent": "<!-- wp:paragraph -->\n<p>When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Quote by Fyodor Dostoevsky</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">This blog post will be transformed...</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"style\":{\"color\":{\"text\":\"#ffffff\",\"background\":\"#709372\"}}} -->\n<p class=\"has-text-color has-background\" style=\"color:#ffffff;background-color:#709372\">If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Quote by Leo Tolstoy</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:separator {\"opacity\":\"css\"} -->\n<hr class=\"wp-block-separator has-css-opacity\"/>\n<!-- /wp:separator -->\n\n<!-- wp:image {\"align\":\"wide\",\"id\":33} -->\n<figure class=\"wp-block-image alignwide\"><img src=\"https://i.ytimg.com/vi/z4KKd2nGlEM/maxresdefault.jpg\" alt=\"Ooops, I got stuck\" class=\"wp-image-33\"/><figcaption class=\"wp-element-caption\">My owner doesn't give me a hat, so I gotta do a hack</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\"><mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">I love these veggies!!!</mark></h3>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><!-- wp:list-item -->\n<li>Eggplant</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Zucchini</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Pumpkin</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Broccoli</li>\n<!-- /wp:list-item --></ul>\n<!-- /wp:list -->\n\n<!-- wp:cover {\"url\":\"https://gatographql-pro.lndo.site/wp-content/uploads/Funny-Dog.jpg\",\"id\":1380,\"dimRatio\":50,\"isDark\":false} -->\n<div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim\"></span><img class=\"wp-block-cover__image-background wp-image-1380\" alt=\"Dog not caring about anything\" src=\"https://gatographql-pro.lndo.site/wp-content/uploads/Funny-Dog.jpg\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title…\",\"fontSize\":\"large\"} -->\n<p class=\"has-text-align-center has-large-font-size\">See if I care</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:cover -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">When going to eat out, I normally go for one of these:</h3>\n<!-- /wp:heading -->\n\n<!-- wp:list {\"ordered\":true} -->\n<ol><!-- wp:list-item -->\n<li>Vegetarian</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Chinese</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Indian</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Thai</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Pizza</li>\n<!-- /wp:list-item --></ol>\n<!-- /wp:list -->\n\n<!-- wp:columns {\"align\":\"wide\"} -->\n<div class=\"wp-block-columns alignwide\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph -->\n<p>If you want to know who controls you, look at who you are not allowed to criticize. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Quote by Voltaire</a>)</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:media-text {\"mediaId\":1362,\"mediaLink\":\"https://gatographql.lndo.site/graphql-api-search-results/\",\"mediaType\":\"image\"} -->\n<div class=\"wp-block-media-text alignwide is-stacked-on-mobile\"><figure class=\"wp-block-media-text__media\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png\" alt=\"In red are the entities with a transformed name\" class=\"wp-image-1362 size-full\"/></figure><div class=\"wp-block-media-text__content\"><!-- wp:paragraph {\"placeholder\":\"Content…\"} -->\n<p>This is the schema</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:media-text --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:verse -->\n<pre class=\"wp-block-verse\">Reality is created by the mind, <strong>we can change our reality by changing our mind</strong>.</pre>\n<!-- /wp:verse --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:button {\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link wp-element-button\" href=\"https://duckduckgo.com/\">Do some duck duck search online</a></div>\n<!-- /wp:button -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">This heading (H3) is boring (Regex test: ${1} #1), but these guys are not</h3>\n<!-- /wp:heading -->\n\n<!-- wp:gallery {\"columns\":3,\"linkTo\":\"none\",\"align\":\"wide\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery alignwide has-nested-images columns-3 is-cropped alignnone\"><!-- wp:image {\"id\":32,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.insider.com/5f44388342f43f001ddfec52\" alt=\"Funny dog in couch\" class=\"wp-image-32\"/><figcaption class=\"wp-element-caption\">Bring me the chips please</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"id\":31,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.pinimg.com/originals/85/a7/f5/85a7f5cee4c93cb3995d1b51e3a0289f.jpg\" alt=\"Even funnier dog\" class=\"wp-image-31\"/><figcaption class=\"wp-element-caption\">Anyone having chips? Don't leave me alone!</figcaption></figure>\n<!-- /wp:image --></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>Our mind is enriched by what we receive, our heart by what we give.</p>\n<!-- /wp:paragraph --><cite>Victor Hugo (French Romantic writer and politician)</cite></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:buttons {\"align\":\"wide\",\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\"}} -->\n<div class=\"wp-block-buttons alignwide\"><!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\">Surprise your mum with a phone call 🤙</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\">Start a conversation with a stranger 😜</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|60\",\"right\":\"var:preset|spacing|60\",\"bottom\":\"var:preset|spacing|60\",\"left\":\"var:preset|spacing|60\"}},\"color\":{\"background\":\"#382998\"}},\"fontSize\":\"extra-large\"} -->\n<div class=\"wp-block-button has-custom-font-size has-extra-large-font-size\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background-color:#382998;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)\">Keep your soul satiated 🙏</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n<!-- wp:pullquote -->\n<figure class=\"wp-block-pullquote\"><blockquote><p>You only know me as you see me, not as I actually am.</p><cite>Immanuel Kant (German philosopher and one of the central Enlightenment thinkers)</cite></blockquote></figure>\n<!-- /wp:pullquote -->\n\n<!-- wp:audio -->\n<figure class=\"wp-block-audio\"><audio controls src=\"https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3\"></audio><figcaption class=\"wp-element-caption\">\"Broke for free\" song</figcaption></figure>\n<!-- /wp:audio -->\n\n<!-- wp:video {\"id\":132,\"tracks\":[]} -->\n<figure class=\"wp-block-video\"><video controls src=\"https://download.samplelib.com/mp4/sample-5s.mp4\"></video><figcaption class=\"wp-element-caption\">Test video of a road in a city</figcaption></figure>\n<!-- /wp:video -->\n\n<!-- wp:preformatted -->\n<pre class=\"wp-block-preformatted\">Be a free thinker and don't accept everything you hear as truth. Be critical and evaluate what you believe in.</pre>\n<!-- /wp:preformatted -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Nothing exists except atoms and empty space; everything else is opinion.<br><br>The man enslaved to wealth can never be honest.<br><br>–Democritus</pre>\n<!-- /wp:verse -->\n\n<!-- wp:table {\"hasFixedLayout\":true,\"align\":\"wide\",\"className\":\"has-fixed-layout\"} -->\n<figure class=\"wp-block-table alignwide has-fixed-layout\"><table class=\"has-fixed-layout\"><tbody><tr><td><strong>What I like</strong></td><td>Petting my 3 cats 😺😺😺 in the morning ☀️</td><td>Riding my bike in the mountain</td></tr><tr><td><strong>What I have</strong></td><td>Not much, just enough to <a href=\"https://keepmegoing.com\">keep me going on</a></td><td>A <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcony with plants</mark></td></tr><tr><td><strong>What I desire</strong></td><td>Attending <strong>conferences</strong> and meeting plenty of <strong>interesting folks</strong> from the community</td><td>To be able to focus on the important things of life</td></tr></tbody></table><figcaption class=\"wp-element-caption\">Some items to consider for new year's resolution?</figcaption></figure>\n<!-- /wp:table -->\n\n<!-- wp:embed {\"url\":\"https://www.youtube.com/watch?v=7Nmz3IjtPh0\",\"type\":\"video\",\"providerNameSlug\":\"youtube\",\"responsive\":true,\"className\":\"wp-embed-aspect-16-9 wp-has-aspect-ratio\"} -->\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\nhttps://www.youtube.com/watch?v=7Nmz3IjtPh0\n</div><figcaption class=\"wp-element-caption\">At this year's WCEU Keynote address in Athens, Greece. <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a> co-founder, Matt Mullenweg, Gutenberg Architect, Matías Ventura, and Executive Director, Josepha Haden Chomphosy reflect on WordPress in 2023 and aspirations for the coming year.</figcaption></figure>\n<!-- /wp:embed -->",
        "coreHeading": [
          {
            "name": "core/heading",
            "attributes": {
              "content": "This blog post will be transformed...",
              "level": 2
            }
          },
          {
            "name": "core/heading",
            "attributes": {
              "level": 3,
              "content": "<mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">I love these veggies!!!</mark>"
            }
          },
          {
            "name": "core/heading",
            "attributes": {
              "level": 3,
              "content": "When going to eat out, I normally go for one of these:"
            }
          },
          {
            "name": "core/heading",
            "attributes": {
              "level": 3,
              "content": "This heading (H3) is boring (Regex test: ${1} #1), but these guys are not"
            }
          }
        ],
        "coreParagraph": [
          {
            "name": "core/paragraph",
            "attributes": {
              "content": "When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Quote by Fyodor Dostoevsky</a>)<br>",
              "dropCap": false
            }
          },
          {
            "name": "core/paragraph",
            "attributes": {
              "style": {
                "color": {
                  "text": "#ffffff",
                  "background": "#709372"
                }
              },
              "content": "If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Quote by Leo Tolstoy</a>)<br>",
              "dropCap": false
            }
          },
          {
            "name": "core/paragraph",
            "attributes": {
              "align": "center",
              "placeholder": "Write title…",
              "fontSize": "large",
              "content": "See if I care",
              "dropCap": false
            }
          },
          {
            "name": "core/paragraph",
            "attributes": {
              "content": "If you want to know who controls you, look at who you are not allowed to criticize. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Quote by Voltaire</a>)",
              "dropCap": false
            }
          },
          {
            "name": "core/paragraph",
            "attributes": {
              "placeholder": "Content…",
              "content": "This is the schema",
              "dropCap": false
            }
          },
          {
            "name": "core/paragraph",
            "attributes": {
              "content": "Our mind is enriched by what we receive, our heart by what we give.",
              "dropCap": false
            }
          }
        ],
        "coreImage": [
          {
            "name": "core/image",
            "attributes": {
              "align": "wide",
              "id": 33,
              "url": "https://i.ytimg.com/vi/z4KKd2nGlEM/maxresdefault.jpg",
              "alt": "Ooops, I got stuck",
              "caption": "My owner doesn't give me a hat, so I gotta do a hack"
            }
          },
          {
            "name": "core/image",
            "attributes": {
              "id": 32,
              "linkDestination": "none",
              "url": "https://i.insider.com/5f44388342f43f001ddfec52",
              "alt": "Funny dog in couch",
              "caption": "Bring me the chips please"
            }
          },
          {
            "name": "core/image",
            "attributes": {
              "id": 31,
              "linkDestination": "none",
              "url": "https://i.pinimg.com/originals/85/a7/f5/85a7f5cee4c93cb3995d1b51e3a0289f.jpg",
              "alt": "Even funnier dog",
              "caption": "Anyone having chips? Don't leave me alone!"
            }
          }
        ],
        "coreButton": [
          {
            "name": "core/button",
            "attributes": {
              "className": "is-style-outline",
              "url": "https://duckduckgo.com/",
              "text": "Do some duck duck search online"
            }
          },
          {
            "name": "core/button",
            "attributes": {
              "style": {
                "color": {
                  "gradient": "linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)"
                }
              },
              "text": "Surprise your mum with a phone call 🤙"
            }
          },
          {
            "name": "core/button",
            "attributes": {
              "style": {
                "color": {
                  "gradient": "linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)"
                }
              },
              "text": "Start a conversation with a stranger 😜"
            }
          },
          {
            "name": "core/button",
            "attributes": {
              "style": {
                "spacing": {
                  "padding": {
                    "top": "var:preset|spacing|60",
                    "right": "var:preset|spacing|60",
                    "bottom": "var:preset|spacing|60",
                    "left": "var:preset|spacing|60"
                  }
                },
                "color": {
                  "background": "#382998"
                }
              },
              "fontSize": "extra-large",
              "text": "Keep your soul satiated 🙏"
            }
          }
        ],
        "coreTable": [
          {
            "name": "core/table",
            "attributes": {
              "hasFixedLayout": true,
              "align": "wide",
              "className": "has-fixed-layout",
              "caption": "Some items to consider for new year's resolution?",
              "head": [],
              "body": [
                {
                  "cells": [
                    {
                      "content": "<strong>What I like</strong>",
                      "tag": "td"
                    },
                    {
                      "content": "Petting my 3 cats 😺😺😺 in the morning ☀️",
                      "tag": "td"
                    },
                    {
                      "content": "Riding my bike in the mountain",
                      "tag": "td"
                    }
                  ]
                },
                {
                  "cells": [
                    {
                      "content": "<strong>What I have</strong>",
                      "tag": "td"
                    },
                    {
                      "content": "Not much, just enough to <a href=\"https://keepmegoing.com\">keep me going on</a>",
                      "tag": "td"
                    },
                    {
                      "content": "A <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcony with plants</mark>",
                      "tag": "td"
                    }
                  ]
                },
                {
                  "cells": [
                    {
                      "content": "<strong>What I desire</strong>",
                      "tag": "td"
                    },
                    {
                      "content": "Attending <strong>conferences</strong> and meeting plenty of <strong>interesting folks</strong> from the community",
                      "tag": "td"
                    },
                    {
                      "content": "To be able to focus on the important things of life",
                      "tag": "td"
                    }
                  ]
                }
              ],
              "foot": []
            }
          }
        ],
        "coreListItem": [
          {
            "name": "core/list-item",
            "attributes": {
              "content": "Eggplant"
            }
          },
          {
            "name": "core/list-item",
            "attributes": {
              "content": "Zucchini"
            }
          },
          {
            "name": "core/list-item",
            "attributes": {
              "content": "Pumpkin"
            }
          },
          {
            "name": "core/list-item",
            "attributes": {
              "content": "Broccoli"
            }
          },
          {
            "name": "core/list-item",
            "attributes": {
              "content": "Vegetarian"
            }
          },
          {
            "name": "core/list-item",
            "attributes": {
              "content": "Chinese"
            }
          },
          {
            "name": "core/list-item",
            "attributes": {
              "content": "Indian"
            }
          },
          {
            "name": "core/list-item",
            "attributes": {
              "content": "Thai"
            }
          },
          {
            "name": "core/list-item",
            "attributes": {
              "content": "Pizza"
            }
          }
        ],
        "coreCover": [
          {
            "name": "core/cover",
            "attributes": {
              "url": "https://gatographql-pro.lndo.site/wp-content/uploads/Funny-Dog.jpg",
              "id": 1380,
              "dimRatio": 50,
              "isDark": false,
              "useFeaturedImage": false,
              "alt": "Dog not caring about anything",
              "hasParallax": false,
              "isRepeated": false,
              "backgroundType": "image",
              "tagName": "div"
            }
          }
        ],
        "coreMediaText": [
          {
            "name": "core/media-text",
            "attributes": {
              "mediaId": 1362,
              "mediaLink": "https://gatographql.lndo.site/graphql-api-search-results/",
              "mediaType": "image",
              "align": "wide",
              "mediaAlt": "In red are the entities with a transformed name",
              "mediaPosition": "left",
              "mediaUrl": "https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png",
              "mediaWidth": 50,
              "isStackedOnMobile": true
            }
          }
        ],
        "coreVerse": [
          {
            "name": "core/verse",
            "attributes": {
              "content": "Reality is created by the mind, <strong>we can change our reality by changing our mind</strong>."
            }
          },
          {
            "name": "core/verse",
            "attributes": {
              "content": "Nothing exists except atoms and empty space; everything else is opinion.<br><br>The man enslaved to wealth can never be honest.<br><br>–Democritus"
            }
          }
        ],
        "coreQuote": [
          {
            "name": "core/quote",
            "attributes": {
              "value": "",
              "citation": "Victor Hugo (French Romantic writer and politician)"
            }
          }
        ],
        "corePullquote": [
          {
            "name": "core/pullquote",
            "attributes": {
              "value": "You only know me as you see me, not as I actually am.",
              "citation": "Immanuel Kant (German philosopher and one of the central Enlightenment thinkers)"
            }
          }
        ],
        "coreAudio": [
          {
            "name": "core/audio",
            "attributes": {
              "src": "https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3",
              "caption": "\"Broke for free\" song"
            }
          }
        ],
        "coreVideo": [
          {
            "name": "core/video",
            "attributes": {
              "id": 132,
              "tracks": [],
              "caption": "Test video of a road in a city",
              "controls": "",
              "preload": "metadata",
              "src": "https://download.samplelib.com/mp4/sample-5s.mp4"
            }
          }
        ],
        "corePreformatted": [
          {
            "name": "core/preformatted",
            "attributes": {
              "content": "Be a free thinker and don't accept everything you hear as truth. Be critical and evaluate what you believe in."
            }
          }
        ],
        "coreEmbed": [
          {
            "name": "core/embed",
            "attributes": {
              "url": "https://www.youtube.com/watch?v=7Nmz3IjtPh0",
              "type": "video",
              "providerNameSlug": "youtube",
              "responsive": true,
              "className": "wp-embed-aspect-16-9 wp-has-aspect-ratio",
              "caption": "At this year's WCEU Keynote address in Athens, Greece. <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a> co-founder, Matt Mullenweg, Gutenberg Architect, Matías Ventura, and Executive Director, Josepha Haden Chomphosy reflect on WordPress in 2023 and aspirations for the coming year.",
              "allowResponsive": true,
              "previewable": true
            }
          }
        ]
      },
      {
        "id": 19,
        "title": "Nested mutations are a must have",
        "rawContent": "<!-- wp:gallery {\"linkTo\":\"none\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery columns-3 is-cropped alignnone\"><ul class=\"blocks-gallery-grid\"><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/zd7Ehu+\" alt=\"\" data-id=\"1706\" class=\"wp-image-1706\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/jXLtzZ+\" alt=\"\" data-id=\"1705\" class=\"wp-image-1705\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/VhP9ud+\" alt=\"\" data-id=\"1704\" class=\"wp-image-1704\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/HB0qXg+\" alt=\"\" data-id=\"1703\" class=\"wp-image-1703\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/5cHfcd+\" alt=\"\" data-id=\"1699\" class=\"wp-image-1699\"/></figure></li></ul></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:heading -->\n<h2>List Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><li>List item 1</li><li>List item 2</li><li>List item 3</li><li>List item 4</li></ul>\n<!-- /wp:list -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Columns Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1701,\"className\":\"layout-column-1\"} -->\n<figure class=\"wp-block-image layout-column-1\"><img src=\"https://d.pr/i/fW6V3V+\" alt=\"\" class=\"wp-image-1701\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph {\"className\":\"layout-column-2\"} -->\n<p class=\"layout-column-2\">Phosfluorescently morph intuitive relationships rather than customer directed human capital. Dynamically customize turnkey information whereas orthogonal processes. Assertively deliver superior leadership skills whereas holistic outsourcing. Enthusiastically iterate enabled best practices vis-a-vis 24/365 communities.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Columns inside Columns (nested inner blocks)</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1701,\"className\":\"layout-column-1\"} -->\n<figure class=\"wp-block-image layout-column-1\"><img src=\"https://d.pr/i/fW6V3V+\" alt=\"\" class=\"wp-image-1701\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"33.33%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:33.33%\"><!-- wp:heading {\"fontSize\":\"large\"} -->\n<h2 class=\"wp-block-heading has-large-font-size\">Life is so rich</h2>\n<!-- /wp:heading -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Life is so dynamic</h3>\n<!-- /wp:heading --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"width\":\"66.66%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:66.66%\"><!-- wp:paragraph -->\n<p>This rhyming poem is the spark that can reignite the fires within you. It challenges you to go out and live your life in the present moment as a “hero” and leave your mark on this world.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1361,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-1024x622.jpg\" alt=\"\" class=\"wp-image-1361\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1362,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png\" alt=\"\" class=\"wp-image-1362\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->",
        "coreHeading": [
          {
            "name": "core/heading",
            "attributes": {
              "content": "List Block",
              "level": 2
            }
          },
          {
            "name": "core/heading",
            "attributes": {
              "className": "has-top-margin",
              "content": "Columns Block",
              "level": 2
            }
          },
          {
            "name": "core/heading",
            "attributes": {
              "content": "Columns inside Columns (nested inner blocks)",
              "level": 2
            }
          },
          {
            "name": "core/heading",
            "attributes": {
              "fontSize": "large",
              "content": "Life is so rich",
              "level": 2
            }
          },
          {
            "name": "core/heading",
            "attributes": {
              "level": 3,
              "content": "Life is so dynamic"
            }
          }
        ],
        "coreParagraph": [
          {
            "name": "core/paragraph",
            "attributes": {
              "className": "layout-column-2",
              "content": "Phosfluorescently morph intuitive relationships rather than customer directed human capital. Dynamically customize turnkey information whereas orthogonal processes. Assertively deliver superior leadership skills whereas holistic outsourcing. Enthusiastically iterate enabled best practices vis-a-vis 24/365 communities.",
              "dropCap": false
            }
          },
          {
            "name": "core/paragraph",
            "attributes": {
              "content": "This rhyming poem is the spark that can reignite the fires within you. It challenges you to go out and live your life in the present moment as a “hero” and leave your mark on this world.",
              "dropCap": false
            }
          }
        ],
        "coreImage": [
          {
            "name": "core/image",
            "attributes": {
              "id": 1701,
              "className": "layout-column-1",
              "url": "https://d.pr/i/fW6V3V+",
              "alt": ""
            }
          },
          {
            "name": "core/image",
            "attributes": {
              "id": 1701,
              "className": "layout-column-1",
              "url": "https://d.pr/i/fW6V3V+",
              "alt": ""
            }
          },
          {
            "name": "core/image",
            "attributes": {
              "id": 1361,
              "sizeSlug": "large",
              "linkDestination": "none",
              "url": "https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-1024x622.jpg",
              "alt": ""
            }
          },
          {
            "name": "core/image",
            "attributes": {
              "id": 1362,
              "sizeSlug": "large",
              "linkDestination": "none",
              "url": "https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png",
              "alt": ""
            }
          }
        ],
        "coreButton": [],
        "coreTable": [],
        "coreListItem": [],
        "coreCover": [],
        "coreMediaText": [],
        "coreVerse": [],
        "coreQuote": [],
        "corePullquote": [],
        "coreAudio": [],
        "coreVideo": [],
        "corePreformatted": [],
        "coreEmbed": []
      }
    ],
    "adaptedToTitle": {
      "19": [
        "Nested mutations are a must have"
      ],
      "40": [
        "Welcome to a single post full of blocks!"
      ]
    },
    "adaptedFromTitle": {
      "19": [
        ""
      ],
      "40": [
        ""
      ]
    },
    "transformations": {
      "meta": {
        "from": {
          "19": [
            ""
          ],
          "40": [
            ""
          ]
        },
        "to": {
          "19": [
            "Las mutaciones anidadas son imprescindibles"
          ],
          "40": [
            "¡Bienvenidos a un solo post lleno de bloques!"
          ]
        }
      },
      "coreHeadingContent": {
        "from": {
          "19": [
            "List Block",
            "Columns Block",
            "Columns inside Columns (nested inner blocks)",
            "Life is so rich",
            "Life is so dynamic"
          ],
          "40": [
            "This blog post will be transformed...",
            "<mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">I love these veggies!!!</mark>",
            "When going to eat out, I normally go for one of these:",
            "This heading (H3) is boring (Regex test: ${1} #1), but these guys are not"
          ]
        },
        "to": {
          "19": [
            "Bloque de lista",
            "Bloque de columnas",
            "Columnas dentro de Columnas (bloques internos anidados)",
            "La vida es tan rica",
            "La vida es tan dinámica"
          ],
          "40": [
            "Esta entrada de blog se transformará...",
            "<mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark>",
            "Cuando voy a comer fuera, normalmente elijo uno de estos:",
            "Este encabezado (H3) es aburrido (prueba Regex: ${1} #1), pero estos tipos no lo son"
          ]
        }
      },
      "coreParagraphContent": {
        "from": {
          "19": [
            "Phosfluorescently morph intuitive relationships rather than customer directed human capital. Dynamically customize turnkey information whereas orthogonal processes. Assertively deliver superior leadership skills whereas holistic outsourcing. Enthusiastically iterate enabled best practices vis-a-vis 24/365 communities.",
            "This rhyming poem is the spark that can reignite the fires within you. It challenges you to go out and live your life in the present moment as a “hero” and leave your mark on this world."
          ],
          "40": [
            "When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Quote by Fyodor Dostoevsky</a>)<br>",
            "If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Quote by Leo Tolstoy</a>)<br>",
            "See if I care",
            "If you want to know who controls you, look at who you are not allowed to criticize. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Quote by Voltaire</a>)",
            "This is the schema",
            "Our mind is enriched by what we receive, our heart by what we give."
          ]
        },
        "to": {
          "19": [
            "Transforme fosforescentemente las relaciones intuitivas en lugar del capital humano dirigido por el cliente. Personalice dinámicamente la información llave en mano mientras que los procesos ortogonales. Ofrezca de manera asertiva habilidades de liderazgo superiores mientras que la subcontratación holística. Repita con entusiasmo las mejores prácticas habilitadas frente a las comunidades 24/365.",
            "Este poema con rima es la chispa que puede reavivar el fuego dentro de ti. Te desafía a salir y vivir tu vida en el momento presente como un \"héroe\" y dejar tu huella en este mundo."
          ],
          "40": [
            "Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br>",
            "Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br>",
            "Mira si me importa",
            "Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)",
            "este es el esquema",
            "Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos."
          ]
        }
      },
      "coreImageAlt": {
        "from": {
          "19": [
            "",
            "",
            "",
            ""
          ],
          "40": [
            "Ooops, I got stuck",
            "Funny dog in couch",
            "Even funnier dog"
          ]
        },
        "to": {
          "19": [
            "",
            "",
            "",
            ""
          ],
          "40": [
            "Vaya, me quedé atascado",
            "Perro divertido en el sofá",
            "Perro aún más divertido"
          ]
        }
      },
      "coreImageCaption": {
        "from": {
          "40": [
            "My owner doesn't give me a hat, so I gotta do a hack",
            "Bring me the chips please",
            "Anyone having chips? Don't leave me alone!"
          ]
        },
        "to": {
          "40": [
            "Mi dueño no me da un sombrero, así que tengo que hacer un truco.",
            "Tráeme las papas por favor",
            "¿Alguien tiene fichas? ¡No me dejes solo!"
          ]
        }
      },
      "coreButtonText": {
        "from": {
          "40": [
            "Do some duck duck search online",
            "Surprise your mum with a phone call 🤙",
            "Start a conversation with a stranger 😜",
            "Keep your soul satiated 🙏"
          ]
        },
        "to": {
          "40": [
            "Haga una búsqueda de pato pato en línea",
            "Sorprende a tu mamá con una llamada telefónica 🤙",
            "Inicia una conversación con un extraño 😜",
            "Mantén tu alma saciada 🙏"
          ]
        }
      },
      "coreTableCaption": {
        "from": {
          "40": [
            "Some items to consider for new year's resolution?"
          ]
        },
        "to": {
          "40": [
            "¿Algunos elementos a considerar para la resolución de año nuevo?"
          ]
        }
      },
      "coreTableBodyCellsContent": {
        "from": {
          "40": [
            "<strong>What I like</strong>",
            "Petting my 3 cats 😺😺😺 in the morning ☀️",
            "Riding my bike in the mountain",
            "<strong>What I have</strong>",
            "Not much, just enough to <a href=\"https://keepmegoing.com\">keep me going on</a>",
            "A <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcony with plants</mark>",
            "<strong>What I desire</strong>",
            "Attending <strong>conferences</strong> and meeting plenty of <strong>interesting folks</strong> from the community",
            "To be able to focus on the important things of life"
          ]
        },
        "to": {
          "40": [
            "<strong>Lo que me gusta</strong>",
            "Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️",
            "Montando mi bicicleta en la montaña",
            "<strong>Lo que tengo</strong>",
            "No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a>",
            "Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark>",
            "<strong>Lo que deseo</strong>",
            "Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad",
            "Ser capaz de concentrarse en las cosas importantes de la vida."
          ]
        }
      },
      "coreListItemContent": {
        "from": {
          "40": [
            "Eggplant",
            "Zucchini",
            "Pumpkin",
            "Broccoli",
            "Vegetarian",
            "Chinese",
            "Indian",
            "Thai",
            "Pizza"
          ]
        },
        "to": {
          "40": [
            "Berenjena",
            "Calabacín",
            "Calabaza",
            "Brócoli",
            "Vegetariano",
            "Chino",
            "indio",
            "tailandés",
            "Pizza"
          ]
        }
      },
      "coreCoverAlt": {
        "from": {
          "40": [
            "Dog not caring about anything"
          ]
        },
        "to": {
          "40": [
            "Perro sin importarle nada"
          ]
        }
      },
      "coreMediaTextAlt": {
        "from": {
          "40": [
            "In red are the entities with a transformed name"
          ]
        },
        "to": {
          "40": [
            "En rojo están las entidades con nombre transformado"
          ]
        }
      },
      "coreVerseContent": {
        "from": {
          "40": [
            "Reality is created by the mind, <strong>we can change our reality by changing our mind</strong>.",
            "Nothing exists except atoms and empty space; everything else is opinion.<br><br>The man enslaved to wealth can never be honest.<br><br>–Democritus"
          ]
        },
        "to": {
          "40": [
            "La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.",
            "Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito"
          ]
        }
      },
      "coreQuoteCitation": {
        "from": {
          "40": [
            "Victor Hugo (French Romantic writer and politician)"
          ]
        },
        "to": {
          "40": [
            "Victor Hugo (escritor y político romántico francés)"
          ]
        }
      },
      "corePullquoteCitation": {
        "from": {
          "40": [
            "Immanuel Kant (German philosopher and one of the central Enlightenment thinkers)"
          ]
        },
        "to": {
          "40": [
            "Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)"
          ]
        }
      },
      "corePullquoteValue": {
        "from": {
          "40": [
            "You only know me as you see me, not as I actually am."
          ]
        },
        "to": {
          "40": [
            "Solo me conoces como me ves, no como soy en realidad."
          ]
        }
      },
      "coreAudioCaption": {
        "from": {
          "40": [
            "\"Broke for free\" song"
          ]
        },
        "to": {
          "40": [
            "Canción \"Broke for free\""
          ]
        }
      },
      "coreVideoCaption": {
        "from": {
          "40": [
            "Test video of a road in a city"
          ]
        },
        "to": {
          "40": [
            "Vídeo de prueba de una carretera en una ciudad"
          ]
        }
      },
      "corePreformattedContent": {
        "from": {
          "40": [
            "Be a free thinker and don't accept everything you hear as truth. Be critical and evaluate what you believe in."
          ]
        },
        "to": {
          "40": [
            "Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees."
          ]
        }
      },
      "coreEmbedCaption": {
        "from": {
          "40": [
            "At this year's WCEU Keynote address in Athens, Greece. <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a> co-founder, Matt Mullenweg, Gutenberg Architect, Matías Ventura, and Executive Director, Josepha Haden Chomphosy reflect on WordPress in 2023 and aspirations for the coming year."
          ]
        },
        "to": {
          "40": [
            "En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año."
          ]
        }
      }
    },
    "escapedRegexStrings": {
      "meta": {
        "from": {
          "19": [
            ""
          ],
          "40": [
            ""
          ]
        },
        "to": {
          "19": [
            "Las mutaciones anidadas son imprescindibles"
          ],
          "40": [
            "¡Bienvenidos a un solo post lleno de bloques!"
          ]
        }
      },
      "coreHeadingContent": {
        "from": {
          "19": [
            "List Block",
            "Columns Block",
            "Columns inside Columns \\(nested inner blocks\\)",
            "Life is so rich",
            "Life is so dynamic"
          ],
          "40": [
            "This blog post will be transformed\\.\\.\\.",
            "<mark style=\"background-color:\\#D1D1E4\" class=\"has-inline-color\">I love these veggies!!!</mark>",
            "When going to eat out, I normally go for one of these:",
            "This heading \\(H3\\) is boring \\(Regex test: \\${1} \\#1\\), but these guys are not"
          ]
        },
        "to": {
          "19": [
            "${1}Bloque de lista${2}",
            "${1}Bloque de columnas${2}",
            "${1}Columnas dentro de Columnas (bloques internos anidados)${2}",
            "${1}La vida es tan rica${2}",
            "${1}La vida es tan dinámica${2}"
          ],
          "40": [
            "${1}Esta entrada de blog se transformará...${2}",
            "${1}<mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark>${2}",
            "${1}Cuando voy a comer fuera, normalmente elijo uno de estos:${2}",
            "${1}Este encabezado (H3) es aburrido (prueba Regex: \\${1} #1), pero estos tipos no lo son${2}"
          ]
        }
      },
      "coreParagraphContent": {
        "from": {
          "19": [
            "Phosfluorescently morph intuitive relationships rather than customer directed human capital\\. Dynamically customize turnkey information whereas orthogonal processes\\. Assertively deliver superior leadership skills whereas holistic outsourcing\\. Enthusiastically iterate enabled best practices vis-a-vis 24/365 communities\\.",
            "This rhyming poem is the spark that can reignite the fires within you\\. It challenges you to go out and live your life in the present moment as a “hero” and leave your mark on this world\\."
          ],
          "40": [
            "When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds\\. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>\\. \\(<a href=\"https://www\\.azquotes\\.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Quote by Fyodor Dostoevsky</a>\\)<br>",
            "If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life\\. \\(<a href=\"https://www\\.azquotes\\.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Quote by Leo Tolstoy</a>\\)<br>",
            "See if I care",
            "If you want to know who controls you, look at who you are not allowed to criticize\\. \\(<a rel=\"noreferrer noopener\" href=\"https://www\\.azquotes\\.com/author/15138-Voltaire\" target=\"_blank\">Quote by Voltaire</a>\\)",
            "This is the schema",
            "Our mind is enriched by what we receive, our heart by what we give\\."
          ]
        },
        "to": {
          "19": [
            "${1}Transforme fosforescentemente las relaciones intuitivas en lugar del capital humano dirigido por el cliente. Personalice dinámicamente la información llave en mano mientras que los procesos ortogonales. Ofrezca de manera asertiva habilidades de liderazgo superiores mientras que la subcontratación holística. Repita con entusiasmo las mejores prácticas habilitadas frente a las comunidades 24/365.${2}",
            "${1}Este poema con rima es la chispa que puede reavivar el fuego dentro de ti. Te desafía a salir y vivir tu vida en el momento presente como un \"héroe\" y dejar tu huella en este mundo.${2}"
          ],
          "40": [
            "${1}Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br>${2}",
            "${1}Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br>${2}",
            "${1}Mira si me importa${2}",
            "${1}Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)${2}",
            "${1}este es el esquema${2}",
            "${1}Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos.${2}"
          ]
        }
      },
      "coreImageAlt": {
        "from": {
          "19": [
            "",
            "",
            "",
            ""
          ],
          "40": [
            "Ooops, I got stuck",
            "Funny dog in couch",
            "Even funnier dog"
          ]
        },
        "to": {
          "19": [
            "${1}${2}",
            "${1}${2}",
            "${1}${2}",
            "${1}${2}"
          ],
          "40": [
            "${1}Vaya, me quedé atascado${2}",
            "${1}Perro divertido en el sofá${2}",
            "${1}Perro aún más divertido${2}"
          ]
        }
      },
      "coreImageCaption": {
        "from": {
          "40": [
            "My owner doesn't give me a hat, so I gotta do a hack",
            "Bring me the chips please",
            "Anyone having chips\\? Don't leave me alone!"
          ]
        },
        "to": {
          "40": [
            "${1}Mi dueño no me da un sombrero, así que tengo que hacer un truco.${2}",
            "${1}Tráeme las papas por favor${2}",
            "${1}¿Alguien tiene fichas? ¡No me dejes solo!${2}"
          ]
        }
      },
      "coreButtonText": {
        "from": {
          "40": [
            "Do some duck duck search online",
            "Surprise your mum with a phone call 🤙",
            "Start a conversation with a stranger 😜",
            "Keep your soul satiated 🙏"
          ]
        },
        "to": {
          "40": [
            "${1}Haga una búsqueda de pato pato en línea${2}",
            "${1}Sorprende a tu mamá con una llamada telefónica 🤙${2}",
            "${1}Inicia una conversación con un extraño 😜${2}",
            "${1}Mantén tu alma saciada 🙏${2}"
          ]
        }
      },
      "coreTableCaption": {
        "from": {
          "40": [
            "Some items to consider for new year's resolution\\?"
          ]
        },
        "to": {
          "40": [
            "${1}¿Algunos elementos a considerar para la resolución de año nuevo?${2}"
          ]
        }
      },
      "coreTableBodyCellsContent": {
        "from": {
          "40": [
            "<strong>What I like</strong>",
            "Petting my 3 cats 😺😺😺 in the morning ☀️",
            "Riding my bike in the mountain",
            "<strong>What I have</strong>",
            "Not much, just enough to <a href=\"https://keepmegoing\\.com\">keep me going on</a>",
            "A <mark style=\"background-color:rgba\\(0, 0, 0, 0\\);color:\\#c22a2a\" class=\"has-inline-color\">balcony with plants</mark>",
            "<strong>What I desire</strong>",
            "Attending <strong>conferences</strong> and meeting plenty of <strong>interesting folks</strong> from the community",
            "To be able to focus on the important things of life"
          ]
        },
        "to": {
          "40": [
            "${1}<strong>Lo que me gusta</strong>${2}",
            "${1}Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️${2}",
            "${1}Montando mi bicicleta en la montaña${2}",
            "${1}<strong>Lo que tengo</strong>${2}",
            "${1}No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a>${2}",
            "${1}Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark>${2}",
            "${1}<strong>Lo que deseo</strong>${2}",
            "${1}Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad${2}",
            "${1}Ser capaz de concentrarse en las cosas importantes de la vida.${2}"
          ]
        }
      },
      "coreListItemContent": {
        "from": {
          "40": [
            "Eggplant",
            "Zucchini",
            "Pumpkin",
            "Broccoli",
            "Vegetarian",
            "Chinese",
            "Indian",
            "Thai",
            "Pizza"
          ]
        },
        "to": {
          "40": [
            "${1}Berenjena${2}",
            "${1}Calabacín${2}",
            "${1}Calabaza${2}",
            "${1}Brócoli${2}",
            "${1}Vegetariano${2}",
            "${1}Chino${2}",
            "${1}indio${2}",
            "${1}tailandés${2}",
            "${1}Pizza${2}"
          ]
        }
      },
      "coreCoverAlt": {
        "from": {
          "40": [
            "Dog not caring about anything"
          ]
        },
        "to": {
          "40": [
            "${1}Perro sin importarle nada${2}"
          ]
        }
      },
      "coreMediaTextAlt": {
        "from": {
          "40": [
            "In red are the entities with a transformed name"
          ]
        },
        "to": {
          "40": [
            "${1}En rojo están las entidades con nombre transformado${2}"
          ]
        }
      },
      "coreVerseContent": {
        "from": {
          "40": [
            "Reality is created by the mind, <strong>we can change our reality by changing our mind</strong>\\.",
            "Nothing exists except atoms and empty space; everything else is opinion\\.<br><br>The man enslaved to wealth can never be honest\\.<br><br>–Democritus"
          ]
        },
        "to": {
          "40": [
            "${1}La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.${2}",
            "${1}Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito${2}"
          ]
        }
      },
      "coreQuoteCitation": {
        "from": {
          "40": [
            "Victor Hugo \\(French Romantic writer and politician\\)"
          ]
        },
        "to": {
          "40": [
            "${1}Victor Hugo (escritor y político romántico francés)${2}"
          ]
        }
      },
      "corePullquoteCitation": {
        "from": {
          "40": [
            "Immanuel Kant \\(German philosopher and one of the central Enlightenment thinkers\\)"
          ]
        },
        "to": {
          "40": [
            "${1}Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)${2}"
          ]
        }
      },
      "corePullquoteValue": {
        "from": {
          "40": [
            "You only know me as you see me, not as I actually am\\."
          ]
        },
        "to": {
          "40": [
            "${1}Solo me conoces como me ves, no como soy en realidad.${2}"
          ]
        }
      },
      "coreAudioCaption": {
        "from": {
          "40": [
            "\"Broke for free\" song"
          ]
        },
        "to": {
          "40": [
            "${1}Canción \"Broke for free\"${2}"
          ]
        }
      },
      "coreVideoCaption": {
        "from": {
          "40": [
            "Test video of a road in a city"
          ]
        },
        "to": {
          "40": [
            "${1}Vídeo de prueba de una carretera en una ciudad${2}"
          ]
        }
      },
      "corePreformattedContent": {
        "from": {
          "40": [
            "Be a free thinker and don't accept everything you hear as truth\\. Be critical and evaluate what you believe in\\."
          ]
        },
        "to": {
          "40": [
            "${1}Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees.${2}"
          ]
        }
      },
      "coreEmbedCaption": {
        "from": {
          "40": [
            "At this year's WCEU Keynote address in Athens, Greece\\. <a href=\"https://www\\.youtube\\.com/hashtag/wordpress\">\\#WordPress</a> co-founder, Matt Mullenweg, Gutenberg Architect, Matías Ventura, and Executive Director, Josepha Haden Chomphosy reflect on WordPress in 2023 and aspirations for the coming year\\."
          ]
        },
        "to": {
          "40": [
            "${1}En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año.${2}"
          ]
        }
      }
    },
    "regexReplacements": {
      "meta": {
        "from": {
          "19": [
            ""
          ],
          "40": [
            ""
          ]
        },
        "to": {
          "19": [
            "Las mutaciones anidadas son imprescindibles"
          ],
          "40": [
            "¡Bienvenidos a un solo post lleno de bloques!"
          ]
        }
      },
      "coreHeadingContent": {
        "from": {
          "19": [
            "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)List Block(</h[1-6]>\\n?<!-- /wp:heading -->)#",
            "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)Columns Block(</h[1-6]>\\n?<!-- /wp:heading -->)#",
            "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)Columns inside Columns \\(nested inner blocks\\)(</h[1-6]>\\n?<!-- /wp:heading -->)#",
            "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)Life is so rich(</h[1-6]>\\n?<!-- /wp:heading -->)#",
            "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)Life is so dynamic(</h[1-6]>\\n?<!-- /wp:heading -->)#"
          ],
          "40": [
            "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)This blog post will be transformed\\.\\.\\.(</h[1-6]>\\n?<!-- /wp:heading -->)#",
            "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)<mark style=\"background-color:\\#D1D1E4\" class=\"has-inline-color\">I love these veggies!!!</mark>(</h[1-6]>\\n?<!-- /wp:heading -->)#",
            "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)When going to eat out, I normally go for one of these:(</h[1-6]>\\n?<!-- /wp:heading -->)#",
            "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)This heading \\(H3\\) is boring \\(Regex test: \\${1} \\#1\\), but these guys are not(</h[1-6]>\\n?<!-- /wp:heading -->)#"
          ]
        },
        "to": {
          "19": [
            "${1}Bloque de lista${2}",
            "${1}Bloque de columnas${2}",
            "${1}Columnas dentro de Columnas (bloques internos anidados)${2}",
            "${1}La vida es tan rica${2}",
            "${1}La vida es tan dinámica${2}"
          ],
          "40": [
            "${1}Esta entrada de blog se transformará...${2}",
            "${1}<mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark>${2}",
            "${1}Cuando voy a comer fuera, normalmente elijo uno de estos:${2}",
            "${1}Este encabezado (H3) es aburrido (prueba Regex: \\${1} #1), pero estos tipos no lo son${2}"
          ]
        }
      },
      "coreParagraphContent": {
        "from": {
          "19": [
            "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)Phosfluorescently morph intuitive relationships rather than customer directed human capital\\. Dynamically customize turnkey information whereas orthogonal processes\\. Assertively deliver superior leadership skills whereas holistic outsourcing\\. Enthusiastically iterate enabled best practices vis-a-vis 24/365 communities\\.(</p>\\n?<!-- /wp:paragraph -->)#",
            "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)This rhyming poem is the spark that can reignite the fires within you\\. It challenges you to go out and live your life in the present moment as a “hero” and leave your mark on this world\\.(</p>\\n?<!-- /wp:paragraph -->)#"
          ],
          "40": [
            "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds\\. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>\\. \\(<a href=\"https://www\\.azquotes\\.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Quote by Fyodor Dostoevsky</a>\\)<br>(</p>\\n?<!-- /wp:paragraph -->)#",
            "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life\\. \\(<a href=\"https://www\\.azquotes\\.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Quote by Leo Tolstoy</a>\\)<br>(</p>\\n?<!-- /wp:paragraph -->)#",
            "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)See if I care(</p>\\n?<!-- /wp:paragraph -->)#",
            "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)If you want to know who controls you, look at who you are not allowed to criticize\\. \\(<a rel=\"noreferrer noopener\" href=\"https://www\\.azquotes\\.com/author/15138-Voltaire\" target=\"_blank\">Quote by Voltaire</a>\\)(</p>\\n?<!-- /wp:paragraph -->)#",
            "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)This is the schema(</p>\\n?<!-- /wp:paragraph -->)#",
            "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)Our mind is enriched by what we receive, our heart by what we give\\.(</p>\\n?<!-- /wp:paragraph -->)#"
          ]
        },
        "to": {
          "19": [
            "${1}Transforme fosforescentemente las relaciones intuitivas en lugar del capital humano dirigido por el cliente. Personalice dinámicamente la información llave en mano mientras que los procesos ortogonales. Ofrezca de manera asertiva habilidades de liderazgo superiores mientras que la subcontratación holística. Repita con entusiasmo las mejores prácticas habilitadas frente a las comunidades 24/365.${2}",
            "${1}Este poema con rima es la chispa que puede reavivar el fuego dentro de ti. Te desafía a salir y vivir tu vida en el momento presente como un \"héroe\" y dejar tu huella en este mundo.${2}"
          ],
          "40": [
            "${1}Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br>${2}",
            "${1}Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br>${2}",
            "${1}Mira si me importa${2}",
            "${1}Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)${2}",
            "${1}este es el esquema${2}",
            "${1}Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos.${2}"
          ]
        }
      },
      "coreImageAlt": {
        "from": {
          "19": [
            "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")(\\\".*>.*\\n?<!-- /wp:image -->)#",
            "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")(\\\".*>.*\\n?<!-- /wp:image -->)#",
            "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")(\\\".*>.*\\n?<!-- /wp:image -->)#",
            "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")(\\\".*>.*\\n?<!-- /wp:image -->)#"
          ],
          "40": [
            "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")Ooops, I got stuck(\\\".*>.*\\n?<!-- /wp:image -->)#",
            "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")Funny dog in couch(\\\".*>.*\\n?<!-- /wp:image -->)#",
            "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")Even funnier dog(\\\".*>.*\\n?<!-- /wp:image -->)#"
          ]
        },
        "to": {
          "19": [
            "${1}${2}",
            "${1}${2}",
            "${1}${2}",
            "${1}${2}"
          ],
          "40": [
            "${1}Vaya, me quedé atascado${2}",
            "${1}Perro divertido en el sofá${2}",
            "${1}Perro aún más divertido${2}"
          ]
        }
      },
      "coreImageCaption": {
        "from": {
          "40": [
            "#(<!-- wp:image .*?-->\\n?.*<figcaption ?.*?>)My owner doesn't give me a hat, so I gotta do a hack(</figcaption>.*\\n?<!-- /wp:image -->)#",
            "#(<!-- wp:image .*?-->\\n?.*<figcaption ?.*?>)Bring me the chips please(</figcaption>.*\\n?<!-- /wp:image -->)#",
            "#(<!-- wp:image .*?-->\\n?.*<figcaption ?.*?>)Anyone having chips\\? Don't leave me alone!(</figcaption>.*\\n?<!-- /wp:image -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}Mi dueño no me da un sombrero, así que tengo que hacer un truco.${2}",
            "${1}Tráeme las papas por favor${2}",
            "${1}¿Alguien tiene fichas? ¡No me dejes solo!${2}"
          ]
        }
      },
      "coreButtonText": {
        "from": {
          "40": [
            "#(<!-- wp:button .*?-->\\n?.*<a ?.*?>)Do some duck duck search online(</a>.*\\n?<!-- /wp:button -->)#",
            "#(<!-- wp:button .*?-->\\n?.*<a ?.*?>)Surprise your mum with a phone call 🤙(</a>.*\\n?<!-- /wp:button -->)#",
            "#(<!-- wp:button .*?-->\\n?.*<a ?.*?>)Start a conversation with a stranger 😜(</a>.*\\n?<!-- /wp:button -->)#",
            "#(<!-- wp:button .*?-->\\n?.*<a ?.*?>)Keep your soul satiated 🙏(</a>.*\\n?<!-- /wp:button -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}Haga una búsqueda de pato pato en línea${2}",
            "${1}Sorprende a tu mamá con una llamada telefónica 🤙${2}",
            "${1}Inicia una conversación con un extraño 😜${2}",
            "${1}Mantén tu alma saciada 🙏${2}"
          ]
        }
      },
      "coreTableCaption": {
        "from": {
          "40": [
            "#(<!-- wp:table .*?-->\\n?.*<figcaption ?.*?>.*)Some items to consider for new year's resolution\\?(.*</figcaption>.*\\n?<!-- /wp:table -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}¿Algunos elementos a considerar para la resolución de año nuevo?${2}"
          ]
        }
      },
      "coreTableBodyCellsContent": {
        "from": {
          "40": [
            "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)<strong>What I like</strong>(.*</table>.*\\n?<!-- /wp:table -->)#",
            "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)Petting my 3 cats 😺😺😺 in the morning ☀️(.*</table>.*\\n?<!-- /wp:table -->)#",
            "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)Riding my bike in the mountain(.*</table>.*\\n?<!-- /wp:table -->)#",
            "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)<strong>What I have</strong>(.*</table>.*\\n?<!-- /wp:table -->)#",
            "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)Not much, just enough to <a href=\"https://keepmegoing\\.com\">keep me going on</a>(.*</table>.*\\n?<!-- /wp:table -->)#",
            "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)A <mark style=\"background-color:rgba\\(0, 0, 0, 0\\);color:\\#c22a2a\" class=\"has-inline-color\">balcony with plants</mark>(.*</table>.*\\n?<!-- /wp:table -->)#",
            "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)<strong>What I desire</strong>(.*</table>.*\\n?<!-- /wp:table -->)#",
            "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)Attending <strong>conferences</strong> and meeting plenty of <strong>interesting folks</strong> from the community(.*</table>.*\\n?<!-- /wp:table -->)#",
            "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)To be able to focus on the important things of life(.*</table>.*\\n?<!-- /wp:table -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}<strong>Lo que me gusta</strong>${2}",
            "${1}Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️${2}",
            "${1}Montando mi bicicleta en la montaña${2}",
            "${1}<strong>Lo que tengo</strong>${2}",
            "${1}No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a>${2}",
            "${1}Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark>${2}",
            "${1}<strong>Lo que deseo</strong>${2}",
            "${1}Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad${2}",
            "${1}Ser capaz de concentrarse en las cosas importantes de la vida.${2}"
          ]
        }
      },
      "coreListItemContent": {
        "from": {
          "40": [
            "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Eggplant(</li>\\n?<!-- /wp:list-item -->)#",
            "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Zucchini(</li>\\n?<!-- /wp:list-item -->)#",
            "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Pumpkin(</li>\\n?<!-- /wp:list-item -->)#",
            "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Broccoli(</li>\\n?<!-- /wp:list-item -->)#",
            "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Vegetarian(</li>\\n?<!-- /wp:list-item -->)#",
            "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Chinese(</li>\\n?<!-- /wp:list-item -->)#",
            "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Indian(</li>\\n?<!-- /wp:list-item -->)#",
            "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Thai(</li>\\n?<!-- /wp:list-item -->)#",
            "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Pizza(</li>\\n?<!-- /wp:list-item -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}Berenjena${2}",
            "${1}Calabacín${2}",
            "${1}Calabaza${2}",
            "${1}Brócoli${2}",
            "${1}Vegetariano${2}",
            "${1}Chino${2}",
            "${1}indio${2}",
            "${1}tailandés${2}",
            "${1}Pizza${2}"
          ]
        }
      },
      "coreCoverAlt": {
        "from": {
          "40": [
            "#(<!-- wp:cover .*?-->\\n?.*<img .*?alt=\\\")Dog not caring about anything(\\\".*>.*\\n?<!-- /wp:cover -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}Perro sin importarle nada${2}"
          ]
        }
      },
      "coreMediaTextAlt": {
        "from": {
          "40": [
            "#(<!-- wp:media-text .*?-->\\n?<div .*><figure .*><img .*?alt=\\\")In red are the entities with a transformed name(\\\")#"
          ]
        },
        "to": {
          "40": [
            "${1}En rojo están las entidades con nombre transformado${2}"
          ]
        }
      },
      "coreVerseContent": {
        "from": {
          "40": [
            "#(<!-- wp:verse .*?-->\\n?<pre ?.*?>)Reality is created by the mind, <strong>we can change our reality by changing our mind</strong>\\.(</pre>\\n?<!-- /wp:verse -->)#",
            "#(<!-- wp:verse .*?-->\\n?<pre ?.*?>)Nothing exists except atoms and empty space; everything else is opinion\\.<br><br>The man enslaved to wealth can never be honest\\.<br><br>–Democritus(</pre>\\n?<!-- /wp:verse -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.${2}",
            "${1}Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito${2}"
          ]
        }
      },
      "coreQuoteCitation": {
        "from": {
          "40": [
            "#(<!-- wp:quote .*?-->\\n?<blockquote ?.*?>.*<cite ?.*?>)Victor Hugo \\(French Romantic writer and politician\\)(</cite></blockquote>\\n?<!-- /wp:quote -->)#s"
          ]
        },
        "to": {
          "40": [
            "${1}Victor Hugo (escritor y político romántico francés)${2}"
          ]
        }
      },
      "corePullquoteCitation": {
        "from": {
          "40": [
            "#(<!-- wp:pullquote .*?-->\\n?<figure ?.*?><blockquote ?.*?><p ?.*?>.*</p><cite ?.*?>)Immanuel Kant \\(German philosopher and one of the central Enlightenment thinkers\\)(</cite></blockquote></figure>\\n?<!-- /wp:pullquote -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)${2}"
          ]
        }
      },
      "corePullquoteValue": {
        "from": {
          "40": [
            "#(<!-- wp:pullquote .*?-->\\n?<figure ?.*?><blockquote ?.*?><p ?.*?>)You only know me as you see me, not as I actually am\\.(</p><cite ?.*?>.*</cite></blockquote></figure>\\n?<!-- /wp:pullquote -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}Solo me conoces como me ves, no como soy en realidad.${2}"
          ]
        }
      },
      "coreAudioCaption": {
        "from": {
          "40": [
            "#(<!-- wp:audio .*?-->\\n?<figure ?.*?><audio ?.*?>.*</audio><figcaption ?.*?>)\"Broke for free\" song(</figcaption></figure>\\n?<!-- /wp:audio -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}Canción \"Broke for free\"${2}"
          ]
        }
      },
      "coreVideoCaption": {
        "from": {
          "40": [
            "#(<!-- wp:video .*?-->\\n?<figure ?.*?><video ?.*?>.*</video><figcaption ?.*?>)Test video of a road in a city(</figcaption></figure>\\n?<!-- /wp:video -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}Vídeo de prueba de una carretera en una ciudad${2}"
          ]
        }
      },
      "corePreformattedContent": {
        "from": {
          "40": [
            "#(<!-- wp:preformatted .*?-->\\n?<pre ?.*?>)Be a free thinker and don't accept everything you hear as truth\\. Be critical and evaluate what you believe in\\.(</pre>\\n?<!-- /wp:preformatted -->)#"
          ]
        },
        "to": {
          "40": [
            "${1}Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees.${2}"
          ]
        }
      },
      "coreEmbedCaption": {
        "from": {
          "40": [
            "#(<!-- wp:embed .*?-->\\n?<figure ?.*?><div ?.*?>.*</div><figcaption ?.*?>)At this year's WCEU Keynote address in Athens, Greece\\. <a href=\"https://www\\.youtube\\.com/hashtag/wordpress\">\\#WordPress</a> co-founder, Matt Mullenweg, Gutenberg Architect, Matías Ventura, and Executive Director, Josepha Haden Chomphosy reflect on WordPress in 2023 and aspirations for the coming year\\.(</figcaption></figure>\\n?<!-- /wp:embed -->)#s"
          ]
        },
        "to": {
          "40": [
            "${1}En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año.${2}"
          ]
        }
      }
    },
    "transformedRawContent": {
      "19": "<!-- wp:gallery {\"linkTo\":\"none\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery columns-3 is-cropped alignnone\"><ul class=\"blocks-gallery-grid\"><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/zd7Ehu+\" alt=\"\" data-id=\"1706\" class=\"wp-image-1706\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/jXLtzZ+\" alt=\"\" data-id=\"1705\" class=\"wp-image-1705\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/VhP9ud+\" alt=\"\" data-id=\"1704\" class=\"wp-image-1704\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/HB0qXg+\" alt=\"\" data-id=\"1703\" class=\"wp-image-1703\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/5cHfcd+\" alt=\"\" data-id=\"1699\" class=\"wp-image-1699\"/></figure></li></ul></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:heading -->\n<h2>Bloque de lista</h2>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><li>List item 1</li><li>List item 2</li><li>List item 3</li><li>List item 4</li></ul>\n<!-- /wp:list -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Bloque de columnas</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1701,\"className\":\"layout-column-1\"} -->\n<figure class=\"wp-block-image layout-column-1\"><img src=\"https://d.pr/i/fW6V3V+\" alt=\"\" class=\"wp-image-1701\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph {\"className\":\"layout-column-2\"} -->\n<p class=\"layout-column-2\">Transforme fosforescentemente las relaciones intuitivas en lugar del capital humano dirigido por el cliente. Personalice dinámicamente la información llave en mano mientras que los procesos ortogonales. Ofrezca de manera asertiva habilidades de liderazgo superiores mientras que la subcontratación holística. Repita con entusiasmo las mejores prácticas habilitadas frente a las comunidades 24/365.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Columnas dentro de Columnas (bloques internos anidados)</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1701,\"className\":\"layout-column-1\"} -->\n<figure class=\"wp-block-image layout-column-1\"><img src=\"https://d.pr/i/fW6V3V+\" alt=\"\" class=\"wp-image-1701\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"33.33%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:33.33%\"><!-- wp:heading {\"fontSize\":\"large\"} -->\n<h2 class=\"wp-block-heading has-large-font-size\">La vida es tan rica</h2>\n<!-- /wp:heading -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">La vida es tan dinámica</h3>\n<!-- /wp:heading --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"width\":\"66.66%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:66.66%\"><!-- wp:paragraph -->\n<p>Este poema con rima es la chispa que puede reavivar el fuego dentro de ti. Te desafía a salir y vivir tu vida en el momento presente como un \"héroe\" y dejar tu huella en este mundo.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1361,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-1024x622.jpg\" alt=\"\" class=\"wp-image-1361\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1362,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png\" alt=\"\" class=\"wp-image-1362\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->",
      "40": "<!-- wp:paragraph -->\n<p>Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Esta entrada de blog se transformará...</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"style\":{\"color\":{\"text\":\"#ffffff\",\"background\":\"#709372\"}}} -->\n<p class=\"has-text-color has-background\" style=\"color:#ffffff;background-color:#709372\">Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:separator {\"opacity\":\"css\"} -->\n<hr class=\"wp-block-separator has-css-opacity\"/>\n<!-- /wp:separator -->\n\n<!-- wp:image {\"align\":\"wide\",\"id\":33} -->\n<figure class=\"wp-block-image alignwide\"><img src=\"https://i.ytimg.com/vi/z4KKd2nGlEM/maxresdefault.jpg\" alt=\"Vaya, me quedé atascado\" class=\"wp-image-33\"/><figcaption class=\"wp-element-caption\">Mi dueño no me da un sombrero, así que tengo que hacer un truco.</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\"><mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark></h3>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><!-- wp:list-item -->\n<li>Berenjena</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabacín</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabaza</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Brócoli</li>\n<!-- /wp:list-item --></ul>\n<!-- /wp:list -->\n\n<!-- wp:cover {\"url\":\"https://gatographql-pro.lndo.site/wp-content/uploads/Funny-Dog.jpg\",\"id\":1380,\"dimRatio\":50,\"isDark\":false} -->\n<div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim\"></span><img class=\"wp-block-cover__image-background wp-image-1380\" alt=\"Dog not caring about anything\" src=\"https://gatographql-pro.lndo.site/wp-content/uploads/Funny-Dog.jpg\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title…\",\"fontSize\":\"large\"} -->\n<p class=\"has-text-align-center has-large-font-size\">Mira si me importa</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:cover -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Cuando voy a comer fuera, normalmente elijo uno de estos:</h3>\n<!-- /wp:heading -->\n\n<!-- wp:list {\"ordered\":true} -->\n<ol><!-- wp:list-item -->\n<li>Vegetariano</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Chino</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>indio</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>tailandés</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Pizza</li>\n<!-- /wp:list-item --></ol>\n<!-- /wp:list -->\n\n<!-- wp:columns {\"align\":\"wide\"} -->\n<div class=\"wp-block-columns alignwide\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph -->\n<p>Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:media-text {\"mediaId\":1362,\"mediaLink\":\"https://gatographql.lndo.site/graphql-api-search-results/\",\"mediaType\":\"image\"} -->\n<div class=\"wp-block-media-text alignwide is-stacked-on-mobile\"><figure class=\"wp-block-media-text__media\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png\" alt=\"En rojo están las entidades con nombre transformado\" class=\"wp-image-1362 size-full\"/></figure><div class=\"wp-block-media-text__content\"><!-- wp:paragraph {\"placeholder\":\"Content…\"} -->\n<p>este es el esquema</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:media-text --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:verse -->\n<pre class=\"wp-block-verse\">La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.</pre>\n<!-- /wp:verse --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:button {\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link wp-element-button\" href=\"https://duckduckgo.com/\">Haga una búsqueda de pato pato en línea</a></div>\n<!-- /wp:button -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Este encabezado (H3) es aburrido (prueba Regex: ${1} #1), pero estos tipos no lo son</h3>\n<!-- /wp:heading -->\n\n<!-- wp:gallery {\"columns\":3,\"linkTo\":\"none\",\"align\":\"wide\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery alignwide has-nested-images columns-3 is-cropped alignnone\"><!-- wp:image {\"id\":32,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.insider.com/5f44388342f43f001ddfec52\" alt=\"Perro divertido en el sofá\" class=\"wp-image-32\"/><figcaption class=\"wp-element-caption\">Tráeme las papas por favor</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"id\":31,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.pinimg.com/originals/85/a7/f5/85a7f5cee4c93cb3995d1b51e3a0289f.jpg\" alt=\"Perro aún más divertido\" class=\"wp-image-31\"/><figcaption class=\"wp-element-caption\">¿Alguien tiene fichas? ¡No me dejes solo!</figcaption></figure>\n<!-- /wp:image --></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos.</p>\n<!-- /wp:paragraph --><cite>Victor Hugo (escritor y político romántico francés)</cite></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:buttons {\"align\":\"wide\",\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\"}} -->\n<div class=\"wp-block-buttons alignwide\"><!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\">Sorprende a tu mamá con una llamada telefónica 🤙</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\">Inicia una conversación con un extraño 😜</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|60\",\"right\":\"var:preset|spacing|60\",\"bottom\":\"var:preset|spacing|60\",\"left\":\"var:preset|spacing|60\"}},\"color\":{\"background\":\"#382998\"}},\"fontSize\":\"extra-large\"} -->\n<div class=\"wp-block-button has-custom-font-size has-extra-large-font-size\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background-color:#382998;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)\">Mantén tu alma saciada 🙏</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n<!-- wp:pullquote -->\n<figure class=\"wp-block-pullquote\"><blockquote><p>Solo me conoces como me ves, no como soy en realidad.</p><cite>Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)</cite></blockquote></figure>\n<!-- /wp:pullquote -->\n\n<!-- wp:audio -->\n<figure class=\"wp-block-audio\"><audio controls src=\"https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3\"></audio><figcaption class=\"wp-element-caption\">Canción \"Broke for free\"</figcaption></figure>\n<!-- /wp:audio -->\n\n<!-- wp:video {\"id\":132,\"tracks\":[]} -->\n<figure class=\"wp-block-video\"><video controls src=\"https://download.samplelib.com/mp4/sample-5s.mp4\"></video><figcaption class=\"wp-element-caption\">Vídeo de prueba de una carretera en una ciudad</figcaption></figure>\n<!-- /wp:video -->\n\n<!-- wp:preformatted -->\n<pre class=\"wp-block-preformatted\">Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees.</pre>\n<!-- /wp:preformatted -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito</pre>\n<!-- /wp:verse -->\n\n<!-- wp:table {\"hasFixedLayout\":true,\"align\":\"wide\",\"className\":\"has-fixed-layout\"} -->\n<figure class=\"wp-block-table alignwide has-fixed-layout\"><table class=\"has-fixed-layout\"><tbody><tr><td><strong>Lo que me gusta</strong></td><td>Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️</td><td>Montando mi bicicleta en la montaña</td></tr><tr><td><strong>Lo que tengo</strong></td><td>No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a></td><td>Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark></td></tr><tr><td><strong>Lo que deseo</strong></td><td>Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad</td><td>Ser capaz de concentrarse en las cosas importantes de la vida.</td></tr></tbody></table><figcaption class=\"wp-element-caption\">¿Algunos elementos a considerar para la resolución de año nuevo?</figcaption></figure>\n<!-- /wp:table -->\n\n<!-- wp:embed {\"url\":\"https://www.youtube.com/watch?v=7Nmz3IjtPh0\",\"type\":\"video\",\"providerNameSlug\":\"youtube\",\"responsive\":true,\"className\":\"wp-embed-aspect-16-9 wp-has-aspect-ratio\"} -->\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\nhttps://www.youtube.com/watch?v=7Nmz3IjtPh0\n</div><figcaption class=\"wp-element-caption\">En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año.</figcaption></figure>\n<!-- /wp:embed -->"
    },
    "transformedMeta": {
      "19": "Las mutaciones anidadas son imprescindibles",
      "40": "¡Bienvenidos a un solo post lleno de bloques!"
    },
    "updatePosts": [
      {
        "id": 40,
        "transformedRawContent": "<!-- wp:paragraph -->\n<p>Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Esta entrada de blog se transformará...</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"style\":{\"color\":{\"text\":\"#ffffff\",\"background\":\"#709372\"}}} -->\n<p class=\"has-text-color has-background\" style=\"color:#ffffff;background-color:#709372\">Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:separator {\"opacity\":\"css\"} -->\n<hr class=\"wp-block-separator has-css-opacity\"/>\n<!-- /wp:separator -->\n\n<!-- wp:image {\"align\":\"wide\",\"id\":33} -->\n<figure class=\"wp-block-image alignwide\"><img src=\"https://i.ytimg.com/vi/z4KKd2nGlEM/maxresdefault.jpg\" alt=\"Vaya, me quedé atascado\" class=\"wp-image-33\"/><figcaption class=\"wp-element-caption\">Mi dueño no me da un sombrero, así que tengo que hacer un truco.</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\"><mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark></h3>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><!-- wp:list-item -->\n<li>Berenjena</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabacín</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabaza</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Brócoli</li>\n<!-- /wp:list-item --></ul>\n<!-- /wp:list -->\n\n<!-- wp:cover {\"url\":\"https://gatographql-pro.lndo.site/wp-content/uploads/Funny-Dog.jpg\",\"id\":1380,\"dimRatio\":50,\"isDark\":false} -->\n<div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim\"></span><img class=\"wp-block-cover__image-background wp-image-1380\" alt=\"Dog not caring about anything\" src=\"https://gatographql-pro.lndo.site/wp-content/uploads/Funny-Dog.jpg\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title…\",\"fontSize\":\"large\"} -->\n<p class=\"has-text-align-center has-large-font-size\">Mira si me importa</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:cover -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Cuando voy a comer fuera, normalmente elijo uno de estos:</h3>\n<!-- /wp:heading -->\n\n<!-- wp:list {\"ordered\":true} -->\n<ol><!-- wp:list-item -->\n<li>Vegetariano</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Chino</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>indio</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>tailandés</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Pizza</li>\n<!-- /wp:list-item --></ol>\n<!-- /wp:list -->\n\n<!-- wp:columns {\"align\":\"wide\"} -->\n<div class=\"wp-block-columns alignwide\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph -->\n<p>Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:media-text {\"mediaId\":1362,\"mediaLink\":\"https://gatographql.lndo.site/graphql-api-search-results/\",\"mediaType\":\"image\"} -->\n<div class=\"wp-block-media-text alignwide is-stacked-on-mobile\"><figure class=\"wp-block-media-text__media\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png\" alt=\"En rojo están las entidades con nombre transformado\" class=\"wp-image-1362 size-full\"/></figure><div class=\"wp-block-media-text__content\"><!-- wp:paragraph {\"placeholder\":\"Content…\"} -->\n<p>este es el esquema</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:media-text --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:verse -->\n<pre class=\"wp-block-verse\">La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.</pre>\n<!-- /wp:verse --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:button {\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link wp-element-button\" href=\"https://duckduckgo.com/\">Haga una búsqueda de pato pato en línea</a></div>\n<!-- /wp:button -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Este encabezado (H3) es aburrido (prueba Regex: ${1} #1), pero estos tipos no lo son</h3>\n<!-- /wp:heading -->\n\n<!-- wp:gallery {\"columns\":3,\"linkTo\":\"none\",\"align\":\"wide\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery alignwide has-nested-images columns-3 is-cropped alignnone\"><!-- wp:image {\"id\":32,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.insider.com/5f44388342f43f001ddfec52\" alt=\"Perro divertido en el sofá\" class=\"wp-image-32\"/><figcaption class=\"wp-element-caption\">Tráeme las papas por favor</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"id\":31,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.pinimg.com/originals/85/a7/f5/85a7f5cee4c93cb3995d1b51e3a0289f.jpg\" alt=\"Perro aún más divertido\" class=\"wp-image-31\"/><figcaption class=\"wp-element-caption\">¿Alguien tiene fichas? ¡No me dejes solo!</figcaption></figure>\n<!-- /wp:image --></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos.</p>\n<!-- /wp:paragraph --><cite>Victor Hugo (escritor y político romántico francés)</cite></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:buttons {\"align\":\"wide\",\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\"}} -->\n<div class=\"wp-block-buttons alignwide\"><!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\">Sorprende a tu mamá con una llamada telefónica 🤙</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\">Inicia una conversación con un extraño 😜</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|60\",\"right\":\"var:preset|spacing|60\",\"bottom\":\"var:preset|spacing|60\",\"left\":\"var:preset|spacing|60\"}},\"color\":{\"background\":\"#382998\"}},\"fontSize\":\"extra-large\"} -->\n<div class=\"wp-block-button has-custom-font-size has-extra-large-font-size\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background-color:#382998;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)\">Mantén tu alma saciada 🙏</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n<!-- wp:pullquote -->\n<figure class=\"wp-block-pullquote\"><blockquote><p>Solo me conoces como me ves, no como soy en realidad.</p><cite>Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)</cite></blockquote></figure>\n<!-- /wp:pullquote -->\n\n<!-- wp:audio -->\n<figure class=\"wp-block-audio\"><audio controls src=\"https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3\"></audio><figcaption class=\"wp-element-caption\">Canción \"Broke for free\"</figcaption></figure>\n<!-- /wp:audio -->\n\n<!-- wp:video {\"id\":132,\"tracks\":[]} -->\n<figure class=\"wp-block-video\"><video controls src=\"https://download.samplelib.com/mp4/sample-5s.mp4\"></video><figcaption class=\"wp-element-caption\">Vídeo de prueba de una carretera en una ciudad</figcaption></figure>\n<!-- /wp:video -->\n\n<!-- wp:preformatted -->\n<pre class=\"wp-block-preformatted\">Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees.</pre>\n<!-- /wp:preformatted -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito</pre>\n<!-- /wp:verse -->\n\n<!-- wp:table {\"hasFixedLayout\":true,\"align\":\"wide\",\"className\":\"has-fixed-layout\"} -->\n<figure class=\"wp-block-table alignwide has-fixed-layout\"><table class=\"has-fixed-layout\"><tbody><tr><td><strong>Lo que me gusta</strong></td><td>Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️</td><td>Montando mi bicicleta en la montaña</td></tr><tr><td><strong>Lo que tengo</strong></td><td>No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a></td><td>Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark></td></tr><tr><td><strong>Lo que deseo</strong></td><td>Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad</td><td>Ser capaz de concentrarse en las cosas importantes de la vida.</td></tr></tbody></table><figcaption class=\"wp-element-caption\">¿Algunos elementos a considerar para la resolución de año nuevo?</figcaption></figure>\n<!-- /wp:table -->\n\n<!-- wp:embed {\"url\":\"https://www.youtube.com/watch?v=7Nmz3IjtPh0\",\"type\":\"video\",\"providerNameSlug\":\"youtube\",\"responsive\":true,\"className\":\"wp-embed-aspect-16-9 wp-has-aspect-ratio\"} -->\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\nhttps://www.youtube.com/watch?v=7Nmz3IjtPh0\n</div><figcaption class=\"wp-element-caption\">En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año.</figcaption></figure>\n<!-- /wp:embed -->",
        "transformedTitle": "¡Bienvenidos a un solo post lleno de bloques!",
        "update": {
          "status": "SUCCESS",
          "errors": null,
          "post": {
            "id": 40,
            "title": "¡Bienvenidos a un solo post lleno de bloques!",
            "rawContent": "<!-- wp:paragraph -->\n<p>Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Esta entrada de blog se transformará...</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"style\":{\"color\":{\"text\":\"#ffffff\",\"background\":\"#709372\"}}} -->\n<p class=\"has-text-color has-background\" style=\"color:#ffffff;background-color:#709372\">Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:separator {\"opacity\":\"css\"} -->\n<hr class=\"wp-block-separator has-css-opacity\"/>\n<!-- /wp:separator -->\n\n<!-- wp:image {\"align\":\"wide\",\"id\":33} -->\n<figure class=\"wp-block-image alignwide\"><img src=\"https://i.ytimg.com/vi/z4KKd2nGlEM/maxresdefault.jpg\" alt=\"Vaya, me quedé atascado\" class=\"wp-image-33\"/><figcaption class=\"wp-element-caption\">Mi dueño no me da un sombrero, así que tengo que hacer un truco.</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\"><mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark></h3>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><!-- wp:list-item -->\n<li>Berenjena</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabacín</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabaza</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Brócoli</li>\n<!-- /wp:list-item --></ul>\n<!-- /wp:list -->\n\n<!-- wp:cover {\"url\":\"https://gatographql-pro.lndo.site/wp-content/uploads/Funny-Dog.jpg\",\"id\":1380,\"dimRatio\":50,\"isDark\":false} -->\n<div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim\"></span><img class=\"wp-block-cover__image-background wp-image-1380\" alt=\"Dog not caring about anything\" src=\"https://gatographql-pro.lndo.site/wp-content/uploads/Funny-Dog.jpg\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title…\",\"fontSize\":\"large\"} -->\n<p class=\"has-text-align-center has-large-font-size\">Mira si me importa</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:cover -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Cuando voy a comer fuera, normalmente elijo uno de estos:</h3>\n<!-- /wp:heading -->\n\n<!-- wp:list {\"ordered\":true} -->\n<ol><!-- wp:list-item -->\n<li>Vegetariano</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Chino</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>indio</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>tailandés</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Pizza</li>\n<!-- /wp:list-item --></ol>\n<!-- /wp:list -->\n\n<!-- wp:columns {\"align\":\"wide\"} -->\n<div class=\"wp-block-columns alignwide\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph -->\n<p>Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:media-text {\"mediaId\":1362,\"mediaLink\":\"https://gatographql.lndo.site/graphql-api-search-results/\",\"mediaType\":\"image\"} -->\n<div class=\"wp-block-media-text alignwide is-stacked-on-mobile\"><figure class=\"wp-block-media-text__media\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png\" alt=\"En rojo están las entidades con nombre transformado\" class=\"wp-image-1362 size-full\"/></figure><div class=\"wp-block-media-text__content\"><!-- wp:paragraph {\"placeholder\":\"Content…\"} -->\n<p>este es el esquema</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:media-text --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:verse -->\n<pre class=\"wp-block-verse\">La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.</pre>\n<!-- /wp:verse --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:button {\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link wp-element-button\" href=\"https://duckduckgo.com/\">Haga una búsqueda de pato pato en línea</a></div>\n<!-- /wp:button -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Este encabezado (H3) es aburrido (prueba Regex: ${1} #1), pero estos tipos no lo son</h3>\n<!-- /wp:heading -->\n\n<!-- wp:gallery {\"columns\":3,\"linkTo\":\"none\",\"align\":\"wide\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery alignwide has-nested-images columns-3 is-cropped alignnone\"><!-- wp:image {\"id\":32,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.insider.com/5f44388342f43f001ddfec52\" alt=\"Perro divertido en el sofá\" class=\"wp-image-32\"/><figcaption class=\"wp-element-caption\">Tráeme las papas por favor</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"id\":31,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.pinimg.com/originals/85/a7/f5/85a7f5cee4c93cb3995d1b51e3a0289f.jpg\" alt=\"Perro aún más divertido\" class=\"wp-image-31\"/><figcaption class=\"wp-element-caption\">¿Alguien tiene fichas? ¡No me dejes solo!</figcaption></figure>\n<!-- /wp:image --></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos.</p>\n<!-- /wp:paragraph --><cite>Victor Hugo (escritor y político romántico francés)</cite></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:buttons {\"align\":\"wide\",\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\"}} -->\n<div class=\"wp-block-buttons alignwide\"><!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\">Sorprende a tu mamá con una llamada telefónica 🤙</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\">Inicia una conversación con un extraño 😜</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|60\",\"right\":\"var:preset|spacing|60\",\"bottom\":\"var:preset|spacing|60\",\"left\":\"var:preset|spacing|60\"}},\"color\":{\"background\":\"#382998\"}},\"fontSize\":\"extra-large\"} -->\n<div class=\"wp-block-button has-custom-font-size has-extra-large-font-size\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background-color:#382998;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)\">Mantén tu alma saciada 🙏</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n<!-- wp:pullquote -->\n<figure class=\"wp-block-pullquote\"><blockquote><p>Solo me conoces como me ves, no como soy en realidad.</p><cite>Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)</cite></blockquote></figure>\n<!-- /wp:pullquote -->\n\n<!-- wp:audio -->\n<figure class=\"wp-block-audio\"><audio controls src=\"https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3\"></audio><figcaption class=\"wp-element-caption\">Canción \"Broke for free\"</figcaption></figure>\n<!-- /wp:audio -->\n\n<!-- wp:video {\"id\":132,\"tracks\":[]} -->\n<figure class=\"wp-block-video\"><video controls src=\"https://download.samplelib.com/mp4/sample-5s.mp4\"></video><figcaption class=\"wp-element-caption\">Vídeo de prueba de una carretera en una ciudad</figcaption></figure>\n<!-- /wp:video -->\n\n<!-- wp:preformatted -->\n<pre class=\"wp-block-preformatted\">Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees.</pre>\n<!-- /wp:preformatted -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito</pre>\n<!-- /wp:verse -->\n\n<!-- wp:table {\"hasFixedLayout\":true,\"align\":\"wide\",\"className\":\"has-fixed-layout\"} -->\n<figure class=\"wp-block-table alignwide has-fixed-layout\"><table class=\"has-fixed-layout\"><tbody><tr><td><strong>Lo que me gusta</strong></td><td>Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️</td><td>Montando mi bicicleta en la montaña</td></tr><tr><td><strong>Lo que tengo</strong></td><td>No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a></td><td>Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark></td></tr><tr><td><strong>Lo que deseo</strong></td><td>Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad</td><td>Ser capaz de concentrarse en las cosas importantes de la vida.</td></tr></tbody></table><figcaption class=\"wp-element-caption\">¿Algunos elementos a considerar para la resolución de año nuevo?</figcaption></figure>\n<!-- /wp:table -->\n\n<!-- wp:embed {\"url\":\"https://www.youtube.com/watch?v=7Nmz3IjtPh0\",\"type\":\"video\",\"providerNameSlug\":\"youtube\",\"responsive\":true,\"className\":\"wp-embed-aspect-16-9 wp-has-aspect-ratio\"} -->\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\nhttps://www.youtube.com/watch?v=7Nmz3IjtPh0\n</div><figcaption class=\"wp-element-caption\">En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año.</figcaption></figure>\n<!-- /wp:embed -->"
          }
        }
      },
      {
        "id": 19,
        "transformedRawContent": "<!-- wp:gallery {\"linkTo\":\"none\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery columns-3 is-cropped alignnone\"><ul class=\"blocks-gallery-grid\"><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/zd7Ehu+\" alt=\"\" data-id=\"1706\" class=\"wp-image-1706\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/jXLtzZ+\" alt=\"\" data-id=\"1705\" class=\"wp-image-1705\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/VhP9ud+\" alt=\"\" data-id=\"1704\" class=\"wp-image-1704\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/HB0qXg+\" alt=\"\" data-id=\"1703\" class=\"wp-image-1703\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/5cHfcd+\" alt=\"\" data-id=\"1699\" class=\"wp-image-1699\"/></figure></li></ul></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:heading -->\n<h2>Bloque de lista</h2>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><li>List item 1</li><li>List item 2</li><li>List item 3</li><li>List item 4</li></ul>\n<!-- /wp:list -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Bloque de columnas</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1701,\"className\":\"layout-column-1\"} -->\n<figure class=\"wp-block-image layout-column-1\"><img src=\"https://d.pr/i/fW6V3V+\" alt=\"\" class=\"wp-image-1701\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph {\"className\":\"layout-column-2\"} -->\n<p class=\"layout-column-2\">Transforme fosforescentemente las relaciones intuitivas en lugar del capital humano dirigido por el cliente. Personalice dinámicamente la información llave en mano mientras que los procesos ortogonales. Ofrezca de manera asertiva habilidades de liderazgo superiores mientras que la subcontratación holística. Repita con entusiasmo las mejores prácticas habilitadas frente a las comunidades 24/365.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Columnas dentro de Columnas (bloques internos anidados)</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1701,\"className\":\"layout-column-1\"} -->\n<figure class=\"wp-block-image layout-column-1\"><img src=\"https://d.pr/i/fW6V3V+\" alt=\"\" class=\"wp-image-1701\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"33.33%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:33.33%\"><!-- wp:heading {\"fontSize\":\"large\"} -->\n<h2 class=\"wp-block-heading has-large-font-size\">La vida es tan rica</h2>\n<!-- /wp:heading -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">La vida es tan dinámica</h3>\n<!-- /wp:heading --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"width\":\"66.66%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:66.66%\"><!-- wp:paragraph -->\n<p>Este poema con rima es la chispa que puede reavivar el fuego dentro de ti. Te desafía a salir y vivir tu vida en el momento presente como un \"héroe\" y dejar tu huella en este mundo.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1361,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-1024x622.jpg\" alt=\"\" class=\"wp-image-1361\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1362,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png\" alt=\"\" class=\"wp-image-1362\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->",
        "transformedTitle": "Las mutaciones anidadas son imprescindibles",
        "update": {
          "status": "SUCCESS",
          "errors": null,
          "post": {
            "id": 19,
            "title": "Las mutaciones anidadas son imprescindibles",
            "rawContent": "<!-- wp:gallery {\"linkTo\":\"none\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery columns-3 is-cropped alignnone\"><ul class=\"blocks-gallery-grid\"><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/zd7Ehu+\" alt=\"\" data-id=\"1706\" class=\"wp-image-1706\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/jXLtzZ+\" alt=\"\" data-id=\"1705\" class=\"wp-image-1705\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/VhP9ud+\" alt=\"\" data-id=\"1704\" class=\"wp-image-1704\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/HB0qXg+\" alt=\"\" data-id=\"1703\" class=\"wp-image-1703\"/></figure></li><li class=\"blocks-gallery-item\"><figure><img src=\"https://d.pr/i/5cHfcd+\" alt=\"\" data-id=\"1699\" class=\"wp-image-1699\"/></figure></li></ul></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:heading -->\n<h2>Bloque de lista</h2>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><li>List item 1</li><li>List item 2</li><li>List item 3</li><li>List item 4</li></ul>\n<!-- /wp:list -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Bloque de columnas</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1701,\"className\":\"layout-column-1\"} -->\n<figure class=\"wp-block-image layout-column-1\"><img src=\"https://d.pr/i/fW6V3V+\" alt=\"\" class=\"wp-image-1701\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph {\"className\":\"layout-column-2\"} -->\n<p class=\"layout-column-2\">Transforme fosforescentemente las relaciones intuitivas en lugar del capital humano dirigido por el cliente. Personalice dinámicamente la información llave en mano mientras que los procesos ortogonales. Ofrezca de manera asertiva habilidades de liderazgo superiores mientras que la subcontratación holística. Repita con entusiasmo las mejores prácticas habilitadas frente a las comunidades 24/365.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Columnas dentro de Columnas (bloques internos anidados)</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1701,\"className\":\"layout-column-1\"} -->\n<figure class=\"wp-block-image layout-column-1\"><img src=\"https://d.pr/i/fW6V3V+\" alt=\"\" class=\"wp-image-1701\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"33.33%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:33.33%\"><!-- wp:heading {\"fontSize\":\"large\"} -->\n<h2 class=\"wp-block-heading has-large-font-size\">La vida es tan rica</h2>\n<!-- /wp:heading -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">La vida es tan dinámica</h3>\n<!-- /wp:heading --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"width\":\"66.66%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:66.66%\"><!-- wp:paragraph -->\n<p>Este poema con rima es la chispa que puede reavivar el fuego dentro de ti. Te desafía a salir y vivir tu vida en el momento presente como un \"héroe\" y dejar tu huella en este mundo.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1361,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-1024x622.jpg\" alt=\"\" class=\"wp-image-1361\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":1362,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png\" alt=\"\" class=\"wp-image-1362\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->"
          }
        }
      }
    ]
  }
}
```
