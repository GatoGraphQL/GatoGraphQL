# Translating block content in a post to a different language

This recipe demonstrates translating a post to the desired language, with full support for (Gutenberg) blocks.

As we've seen in a previous recipe, we are able to modify properties contained within the post's blocks, while not affecting the block structure, and store the content again.

Translation will follow the same idea, applying directive `@strTranslate` (provided by the [**Google Translate**](https://gatographql.com/extensions/google-translate/) extension) which translates content via the Google Translate API.

After executing the GraphQL query, we can keep editing the translated blog post in the block editor. Check the video:

[Watch “Translating a blog post with blocks (Gutenberg integration demo)” in Vimeo](https://vimeo.com/836876255)

## GraphQL query to translate a post to a different language

This GraphQL query translates the post's title and content. Content is transformed by translating all text properties for the following blocks:

- `core/heading`
- `core/paragraph`
- `core/image`
- `core/button`
- `core/table`
- `core/list-item`
- `core/cover`
- `core/media-text`
- `core/verse`
- `core/quote`
- `core/pullquote`
- `core/audio`
- `core/video`
- `core/preformatted`
- `core/embed`

Notice that every text property will have its corresponding regex pattern. In order to support more blocks, you must provide their corresponding regex pattern.

```graphql
query InitializeEmptyVariables {
  emptyArray: _echo(value: [])
    @export(as: "coreHeadingContentItems")
    @export(as: "coreHeadingContentReplacementsFrom")
    @export(as: "coreHeadingContentReplacementsTo")

    @export(as: "coreParagraphContentItems")
    @export(as: "coreParagraphContentReplacementsFrom")
    @export(as: "coreParagraphContentReplacementsTo")

    @export(as: "coreImageAltItems")
    @export(as: "coreImageAltReplacementsFrom")
    @export(as: "coreImageAltReplacementsTo")

    @export(as: "coreImageCaptionItems")
    @export(as: "coreImageCaptionReplacementsFrom")
    @export(as: "coreImageCaptionReplacementsTo")

    @export(as: "coreButtonTextItems")
    @export(as: "coreButtonTextReplacementsFrom")
    @export(as: "coreButtonTextReplacementsTo")

    @export(as: "coreTableCaptionItems")
    @export(as: "coreTableCaptionReplacementsFrom")
    @export(as: "coreTableCaptionReplacementsTo")

    @export(as: "coreTableBodyCellsContentItems")
    @export(as: "coreTableBodyCellsContentReplacementsFrom")
    @export(as: "coreTableBodyCellsContentReplacementsTo")

    @export(as: "coreListItemContentItems")
    @export(as: "coreListItemContentReplacementsFrom")
    @export(as: "coreListItemContentReplacementsTo")

    @export(as: "coreCoverAltItems")
    @export(as: "coreCoverAltReplacementsFrom")
    @export(as: "coreCoverAltReplacementsTo")

    @export(as: "coreMediaTextAltItems")
    @export(as: "coreMediaTextAltReplacementsFrom")
    @export(as: "coreMediaTextAltReplacementsTo")

    @export(as: "coreVerseContentItems")
    @export(as: "coreVerseContentReplacementsFrom")
    @export(as: "coreVerseContentReplacementsTo")

    @export(as: "coreQuoteCitationItems")
    @export(as: "coreQuoteCitationReplacementsFrom")
    @export(as: "coreQuoteCitationReplacementsTo")

    @export(as: "corePullquoteCitationItems")
    @export(as: "corePullquoteCitationReplacementsFrom")
    @export(as: "corePullquoteCitationReplacementsTo")

    @export(as: "corePullquoteValueItems")
    @export(as: "corePullquoteValueReplacementsFrom")
    @export(as: "corePullquoteValueReplacementsTo")

    @export(as: "coreAudioCaptionItems")
    @export(as: "coreAudioCaptionReplacementsFrom")
    @export(as: "coreAudioCaptionReplacementsTo")

    @export(as: "coreVideoCaptionItems")
    @export(as: "coreVideoCaptionReplacementsFrom")
    @export(as: "coreVideoCaptionReplacementsTo")

    @export(as: "corePreformattedContentItems")
    @export(as: "corePreformattedContentReplacementsFrom")
    @export(as: "corePreformattedContentReplacementsTo")

    @export(as: "coreEmbedCaptionItems")
    @export(as: "coreEmbedCaptionReplacementsFrom")
    @export(as: "coreEmbedCaptionReplacementsTo")

    @remove
}

query FetchData($postID: ID!)
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
  @depends(on: "InitializeEmptyVariables")
{
  post(by: { id: $postID } ) {
    id
    title
      @export(as: "title")
    rawContent
      @export(as: "rawContent")
    

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
            )
    
          @underJSONObjectProperty(
            by: { key: "caption" }
            failIfNonExistingKeyOrPath: false
          )
            @export(
              as: "coreImageCaptionItems"
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
            )
    
          @underJSONObjectProperty(
            by: { key: "value" }
            failIfNonExistingKeyOrPath: false
          )
            @export(
              as: "corePullquoteValueItems"
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
          )
  }
}

query TransformData(
  $translateToLang: String!
)
  @depends(on: "FetchData")
{  
  transformations: _echo(value: {
    meta: {
      from: [""],
      to: [$title],
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
        @underEachArrayItem
          @strTranslate(to: $translateToLang)
    @export(as: "transformations")
}

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
    @underEachJSONObjectProperty(
      filter: {
        by: {
          excludeKeys: "meta"
        }
      }
    )
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
      by: { key: "coreHeadingContent" }
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
  
  
    @underJSONObjectProperty(
      by: { key: "coreImageCaption" }
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
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreHeadingContentReplacementsFrom,
      replaceWith: $coreHeadingContentReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreParagraphContentReplacementsFrom,
      replaceWith: $coreParagraphContentReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreImageAltReplacementsFrom,
      replaceWith: $coreImageAltReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreImageCaptionReplacementsFrom,
      replaceWith: $coreImageCaptionReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreButtonTextReplacementsFrom,
      replaceWith: $coreButtonTextReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreTableCaptionReplacementsFrom,
      replaceWith: $coreTableCaptionReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreTableBodyCellsContentReplacementsFrom,
      replaceWith: $coreTableBodyCellsContentReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreListItemContentReplacementsFrom,
      replaceWith: $coreListItemContentReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreCoverAltReplacementsFrom,
      replaceWith: $coreCoverAltReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreMediaTextAltReplacementsFrom,
      replaceWith: $coreMediaTextAltReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreVerseContentReplacementsFrom,
      replaceWith: $coreVerseContentReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreQuoteCitationReplacementsFrom,
      replaceWith: $coreQuoteCitationReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $corePullquoteCitationReplacementsFrom,
      replaceWith: $corePullquoteCitationReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $corePullquoteValueReplacementsFrom,
      replaceWith: $corePullquoteValueReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreAudioCaptionReplacementsFrom,
      replaceWith: $coreAudioCaptionReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreVideoCaptionReplacementsFrom,
      replaceWith: $coreVideoCaptionReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $corePreformattedContentReplacementsFrom,
      replaceWith: $corePreformattedContentReplacementsTo
    )
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreEmbedCaptionReplacementsFrom,
      replaceWith: $coreEmbedCaptionReplacementsTo
    )
    
    @export(as: "transformedRawContent")
}

query PrepareMetaReplacements
  @depends(on: "TransformData")
{  
  transformedMeta: _objectProperty(
    object: $transformations,
    by: { path: "meta.to" }
  )
    @underArrayItem(index: 0)
      @export(as: "transformedTitle")
}

mutation TranslatePost($postID: ID!)
  @depends(on: [
    "ExecuteRegexReplacements",
    "PrepareMetaReplacements"
]) {
  updatePost(input: {
    id: $postID,
    title: $transformedTitle,
    contentAs: {
      html: $transformedRawContent
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
```

Passing these `variables`:

```json
{
  "postID": 40,
  "translateToLang": "es"
}
```

...produces this response:

```json
{
  "data": {
    "post": {
      "id": 40,
      "title": "Welcome to a single post full of blocks!",
      "rawContent": "<!-- wp:paragraph -->\n<p>When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Quote by Fyodor Dostoevsky</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">This blog post will be transformed...</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"style\":{\"color\":{\"text\":\"#ffffff\",\"background\":\"#709372\"}}} -->\n<p class=\"has-text-color has-background\" style=\"color:#ffffff;background-color:#709372\">If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Quote by Leo Tolstoy</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:separator {\"opacity\":\"css\"} -->\n<hr class=\"wp-block-separator has-css-opacity\"/>\n<!-- /wp:separator -->\n\n<!-- wp:image {\"align\":\"wide\",\"id\":33} -->\n<figure class=\"wp-block-image alignwide\"><img src=\"https://i.ytimg.com/vi/z4KKd2nGlEM/maxresdefault.jpg\" alt=\"Ooops, I got stuck\" class=\"wp-image-33\"/><figcaption class=\"wp-element-caption\">My owner doesn't give me a hat, so I gotta do a hack</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\"><mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">I love these veggies!!!</mark></h3>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><!-- wp:list-item -->\n<li>Eggplant</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Zucchini</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Pumpkin</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Broccoli</li>\n<!-- /wp:list-item --></ul>\n<!-- /wp:list -->\n\n<!-- wp:cover {\"url\":\"https://gatographql-pro.lndo.site/wp-content/uploads/2023/06/Funny-Dog.jpg\",\"id\":1080,\"dimRatio\":50,\"isDark\":false} -->\n<div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim\"></span><img class=\"wp-block-cover__image-background wp-image-1080\" alt=\"Dog not caring about anything\" src=\"https://gatographql-pro.lndo.site/wp-content/uploads/2023/06/Funny-Dog.jpg\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title…\",\"fontSize\":\"large\"} -->\n<p class=\"has-text-align-center has-large-font-size\">See if I care</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:cover -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">When going to eat out, I normally go for one of these:</h3>\n<!-- /wp:heading -->\n\n<!-- wp:list {\"ordered\":true} -->\n<ol><!-- wp:list-item -->\n<li>Vegetarian</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Chinese</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Indian</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Thai</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Pizza</li>\n<!-- /wp:list-item --></ol>\n<!-- /wp:list -->\n\n<!-- wp:columns {\"align\":\"wide\"} -->\n<div class=\"wp-block-columns alignwide\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph -->\n<p>If you want to know who controls you, look at who you are not allowed to criticize. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Quote by Voltaire</a>)</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:media-text {\"mediaId\":362,\"mediaLink\":\"https://gatographql.lndo.site/graphql-api-search-results/\",\"mediaType\":\"image\"} -->\n<div class=\"wp-block-media-text alignwide is-stacked-on-mobile\"><figure class=\"wp-block-media-text__media\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-suki-1024x598.png\" alt=\"In red are the entities with a transformed name\" class=\"wp-image-362 size-full\"/></figure><div class=\"wp-block-media-text__content\"><!-- wp:paragraph {\"placeholder\":\"Content…\"} -->\n<p>This is the schema</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:media-text --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:verse -->\n<pre class=\"wp-block-verse\">Reality is created by the mind, <strong>we can change our reality by changing our mind</strong>.</pre>\n<!-- /wp:verse --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:button {\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link wp-element-button\" href=\"https://duckduckgo.com/\">Do some duck duck search online</a></div>\n<!-- /wp:button -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">This heading (H3) is boring (Regex test: $1 #1), but these guys are not</h3>\n<!-- /wp:heading -->\n\n<!-- wp:gallery {\"columns\":3,\"linkTo\":\"none\",\"align\":\"wide\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery alignwide has-nested-images columns-3 is-cropped alignnone\"><!-- wp:image {\"id\":32,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.insider.com/5f44388342f43f001ddfec52\" alt=\"Funny dog in couch\" class=\"wp-image-32\"/><figcaption class=\"wp-element-caption\">Bring me the chips please</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"id\":31,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.pinimg.com/originals/85/a7/f5/85a7f5cee4c93cb3995d1b51e3a0289f.jpg\" alt=\"Even funnier dog\" class=\"wp-image-31\"/><figcaption class=\"wp-element-caption\">Anyone having chips? Don't leave me alone!</figcaption></figure>\n<!-- /wp:image --></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>Our mind is enriched by what we receive, our heart by what we give.</p>\n<!-- /wp:paragraph --><cite>Victor Hugo (French Romantic writer and politician)</cite></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:buttons {\"align\":\"wide\",\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\"}} -->\n<div class=\"wp-block-buttons alignwide\"><!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\">Surprise your mum with a phone call 🤙</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\">Start a conversation with a stranger 😜</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|60\",\"right\":\"var:preset|spacing|60\",\"bottom\":\"var:preset|spacing|60\",\"left\":\"var:preset|spacing|60\"}},\"color\":{\"background\":\"#382998\"}},\"fontSize\":\"extra-large\"} -->\n<div class=\"wp-block-button has-custom-font-size has-extra-large-font-size\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background-color:#382998;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)\">Keep your soul satiated 🙏</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n<!-- wp:pullquote -->\n<figure class=\"wp-block-pullquote\"><blockquote><p>You only know me as you see me, not as I actually am.</p><cite>Immanuel Kant (German philosopher and one of the central Enlightenment thinkers)</cite></blockquote></figure>\n<!-- /wp:pullquote -->\n\n<!-- wp:audio -->\n<figure class=\"wp-block-audio\"><audio controls src=\"https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3\"></audio><figcaption class=\"wp-element-caption\">\"Broke for free\" song</figcaption></figure>\n<!-- /wp:audio -->\n\n<!-- wp:video {\"id\":132,\"tracks\":[]} -->\n<figure class=\"wp-block-video\"><video controls src=\"https://download.samplelib.com/mp4/sample-5s.mp4\"></video><figcaption class=\"wp-element-caption\">Test video of a road in a city</figcaption></figure>\n<!-- /wp:video -->\n\n<!-- wp:preformatted -->\n<pre class=\"wp-block-preformatted\">Be a free thinker and don't accept everything you hear as truth. Be critical and evaluate what you believe in.</pre>\n<!-- /wp:preformatted -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Nothing exists except atoms and empty space; everything else is opinion.<br><br>The man enslaved to wealth can never be honest.<br><br>–Democritus</pre>\n<!-- /wp:verse -->\n\n<!-- wp:table {\"hasFixedLayout\":true,\"align\":\"wide\",\"className\":\"has-fixed-layout\"} -->\n<figure class=\"wp-block-table alignwide has-fixed-layout\"><table class=\"has-fixed-layout\"><tbody><tr><td><strong>What I like</strong></td><td>Petting my 3 cats 😺😺😺 in the morning ☀️</td><td>Riding my bike in the mountain</td></tr><tr><td><strong>What I have</strong></td><td>Not much, just enough to <a href=\"https://keepmegoing.com\">keep me going on</a></td><td>A <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcony with plants</mark></td></tr><tr><td><strong>What I desire</strong></td><td>Attending <strong>conferences</strong> and meeting plenty of <strong>interesting folks</strong> from the community</td><td>To be able to focus on the important things of life</td></tr></tbody></table><figcaption class=\"wp-element-caption\">Some items to consider for new year's resolution?</figcaption></figure>\n<!-- /wp:table -->\n\n<!-- wp:embed {\"url\":\"https://www.youtube.com/watch?v=7Nmz3IjtPh0\",\"type\":\"video\",\"providerNameSlug\":\"youtube\",\"responsive\":true,\"className\":\"wp-embed-aspect-16-9 wp-has-aspect-ratio\"} -->\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\nhttps://www.youtube.com/watch?v=7Nmz3IjtPh0\n</div><figcaption class=\"wp-element-caption\">At this year's WCEU Keynote address in Athens, Greece. <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a> co-founder, Matt Mullenweg, Gutenberg Architect, Matías Ventura, and Executive Director, Josepha Haden Chomphosy reflect on WordPress in 2023 and aspirations for the coming year.</figcaption></figure>\n<!-- /wp:embed -->",
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
            "content": "This heading (H3) is boring (Regex test: $1 #1), but these guys are not"
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
            "url": "https://gatographql-pro.lndo.site/wp-content/uploads/2023/06/Funny-Dog.jpg",
            "id": 1080,
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
            "mediaId": 362,
            "mediaLink": "https://gatographql.lndo.site/graphql-api-search-results/",
            "mediaType": "image",
            "align": "wide",
            "mediaAlt": "In red are the entities with a transformed name",
            "mediaPosition": "left",
            "mediaUrl": "https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-suki-1024x598.png",
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
    "transformations": {
      "meta": {
        "from": [
          ""
        ],
        "to": [
          "¡Bienvenidos a un solo post lleno de bloques!"
        ]
      },
      "coreHeadingContent": {
        "from": [
          "This blog post will be transformed...",
          "<mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">I love these veggies!!!</mark>",
          "When going to eat out, I normally go for one of these:",
          "This heading (H3) is boring (Regex test: $1 #1), but these guys are not"
        ],
        "to": [
          "Esta entrada de blog se transformará...",
          "<mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark>",
          "Cuando voy a comer fuera, normalmente elijo uno de estos:",
          "Este encabezado (H3) es aburrido (prueba Regex: $1 #1), pero estos tipos no lo son"
        ]
      },
      "coreParagraphContent": {
        "from": [
          "When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Quote by Fyodor Dostoevsky</a>)<br>",
          "If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Quote by Leo Tolstoy</a>)<br>",
          "See if I care",
          "If you want to know who controls you, look at who you are not allowed to criticize. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Quote by Voltaire</a>)",
          "This is the schema",
          "Our mind is enriched by what we receive, our heart by what we give."
        ],
        "to": [
          "Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br>",
          "Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br>",
          "Mira si me importa",
          "Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)",
          "este es el esquema",
          "Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos."
        ]
      },
      "coreImageAlt": {
        "from": [
          "Ooops, I got stuck",
          "Funny dog in couch",
          "Even funnier dog"
        ],
        "to": [
          "Vaya, me quedé atascado",
          "Perro divertido en el sofá",
          "Perro aún más divertido"
        ]
      },
      "coreImageCaption": {
        "from": [
          "My owner doesn't give me a hat, so I gotta do a hack",
          "Bring me the chips please",
          "Anyone having chips? Don't leave me alone!"
        ],
        "to": [
          "Mi dueño no me da un sombrero, así que tengo que hacer un truco.",
          "Tráeme las papas por favor",
          "¿Alguien tiene fichas? ¡No me dejes solo!"
        ]
      },
      "coreButtonText": {
        "from": [
          "Do some duck duck search online",
          "Surprise your mum with a phone call 🤙",
          "Start a conversation with a stranger 😜",
          "Keep your soul satiated 🙏"
        ],
        "to": [
          "Haga una búsqueda de pato pato en línea",
          "Sorprende a tu mamá con una llamada telefónica 🤙",
          "Inicia una conversación con un extraño 😜",
          "Mantén tu alma saciada 🙏"
        ]
      },
      "coreTableCaption": {
        "from": [
          "Some items to consider for new year's resolution?"
        ],
        "to": [
          "¿Algunos elementos a considerar para la resolución de año nuevo?"
        ]
      },
      "coreTableBodyCellsContent": {
        "from": [
          "<strong>What I like</strong>",
          "Petting my 3 cats 😺😺😺 in the morning ☀️",
          "Riding my bike in the mountain",
          "<strong>What I have</strong>",
          "Not much, just enough to <a href=\"https://keepmegoing.com\">keep me going on</a>",
          "A <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcony with plants</mark>",
          "<strong>What I desire</strong>",
          "Attending <strong>conferences</strong> and meeting plenty of <strong>interesting folks</strong> from the community",
          "To be able to focus on the important things of life"
        ],
        "to": [
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
      },
      "coreListItemContent": {
        "from": [
          "Eggplant",
          "Zucchini",
          "Pumpkin",
          "Broccoli",
          "Vegetarian",
          "Chinese",
          "Indian",
          "Thai",
          "Pizza"
        ],
        "to": [
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
      },
      "coreCoverAlt": {
        "from": [
          "Dog not caring about anything"
        ],
        "to": [
          "Perro sin importarle nada"
        ]
      },
      "coreMediaTextAlt": {
        "from": [
          "In red are the entities with a transformed name"
        ],
        "to": [
          "En rojo están las entidades con nombre transformado"
        ]
      },
      "coreVerseContent": {
        "from": [
          "Reality is created by the mind, <strong>we can change our reality by changing our mind</strong>.",
          "Nothing exists except atoms and empty space; everything else is opinion.<br><br>The man enslaved to wealth can never be honest.<br><br>–Democritus"
        ],
        "to": [
          "La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.",
          "Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito"
        ]
      },
      "coreQuoteCitation": {
        "from": [
          "Victor Hugo (French Romantic writer and politician)"
        ],
        "to": [
          "Victor Hugo (escritor y político romántico francés)"
        ]
      },
      "corePullquoteCitation": {
        "from": [
          "Immanuel Kant (German philosopher and one of the central Enlightenment thinkers)"
        ],
        "to": [
          "Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)"
        ]
      },
      "corePullquoteValue": {
        "from": [
          "You only know me as you see me, not as I actually am."
        ],
        "to": [
          "Solo me conoces como me ves, no como soy en realidad."
        ]
      },
      "coreAudioCaption": {
        "from": [
          "\"Broke for free\" song"
        ],
        "to": [
          "Canción \"Broke for free\""
        ]
      },
      "coreVideoCaption": {
        "from": [
          "Test video of a road in a city"
        ],
        "to": [
          "Vídeo de prueba de una carretera en una ciudad"
        ]
      },
      "corePreformattedContent": {
        "from": [
          "Be a free thinker and don't accept everything you hear as truth. Be critical and evaluate what you believe in."
        ],
        "to": [
          "Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees."
        ]
      },
      "coreEmbedCaption": {
        "from": [
          "At this year's WCEU Keynote address in Athens, Greece. <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a> co-founder, Matt Mullenweg, Gutenberg Architect, Matías Ventura, and Executive Director, Josepha Haden Chomphosy reflect on WordPress in 2023 and aspirations for the coming year."
        ],
        "to": [
          "En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año."
        ]
      }
    },
    "escapedRegexStrings": {
      "meta": {
        "from": [
          ""
        ],
        "to": [
          "¡Bienvenidos a un solo post lleno de bloques!"
        ]
      },
      "coreHeadingContent": {
        "from": [
          "This blog post will be transformed\\.\\.\\.",
          "<mark style=\"background-color:\\#D1D1E4\" class=\"has-inline-color\">I love these veggies!!!</mark>",
          "When going to eat out, I normally go for one of these:",
          "This heading \\(H3\\) is boring \\(Regex test: \\$1 \\#1\\), but these guys are not"
        ],
        "to": [
          "$1Esta entrada de blog se transformará...$2",
          "$1<mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark>$2",
          "$1Cuando voy a comer fuera, normalmente elijo uno de estos:$2",
          "$1Este encabezado (H3) es aburrido (prueba Regex: \\$1 #1), pero estos tipos no lo son$2"
        ]
      },
      "coreParagraphContent": {
        "from": [
          "When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds\\. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>\\. \\(<a href=\"https://www\\.azquotes\\.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Quote by Fyodor Dostoevsky</a>\\)<br>",
          "If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life\\. \\(<a href=\"https://www\\.azquotes\\.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Quote by Leo Tolstoy</a>\\)<br>",
          "See if I care",
          "If you want to know who controls you, look at who you are not allowed to criticize\\. \\(<a rel=\"noreferrer noopener\" href=\"https://www\\.azquotes\\.com/author/15138-Voltaire\" target=\"_blank\">Quote by Voltaire</a>\\)",
          "This is the schema",
          "Our mind is enriched by what we receive, our heart by what we give\\."
        ],
        "to": [
          "$1Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br>$2",
          "$1Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br>$2",
          "$1Mira si me importa$2",
          "$1Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)$2",
          "$1este es el esquema$2",
          "$1Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos.$2"
        ]
      },
      "coreImageAlt": {
        "from": [
          "Ooops, I got stuck",
          "Funny dog in couch",
          "Even funnier dog"
        ],
        "to": [
          "$1Vaya, me quedé atascado$2",
          "$1Perro divertido en el sofá$2",
          "$1Perro aún más divertido$2"
        ]
      },
      "coreImageCaption": {
        "from": [
          "My owner doesn't give me a hat, so I gotta do a hack",
          "Bring me the chips please",
          "Anyone having chips\\? Don't leave me alone!"
        ],
        "to": [
          "$1Mi dueño no me da un sombrero, así que tengo que hacer un truco.$2",
          "$1Tráeme las papas por favor$2",
          "$1¿Alguien tiene fichas? ¡No me dejes solo!$2"
        ]
      },
      "coreButtonText": {
        "from": [
          "Do some duck duck search online",
          "Surprise your mum with a phone call 🤙",
          "Start a conversation with a stranger 😜",
          "Keep your soul satiated 🙏"
        ],
        "to": [
          "$1Haga una búsqueda de pato pato en línea$2",
          "$1Sorprende a tu mamá con una llamada telefónica 🤙$2",
          "$1Inicia una conversación con un extraño 😜$2",
          "$1Mantén tu alma saciada 🙏$2"
        ]
      },
      "coreTableCaption": {
        "from": [
          "Some items to consider for new year's resolution\\?"
        ],
        "to": [
          "$1¿Algunos elementos a considerar para la resolución de año nuevo?$2"
        ]
      },
      "coreTableBodyCellsContent": {
        "from": [
          "<strong>What I like</strong>",
          "Petting my 3 cats 😺😺😺 in the morning ☀️",
          "Riding my bike in the mountain",
          "<strong>What I have</strong>",
          "Not much, just enough to <a href=\"https://keepmegoing\\.com\">keep me going on</a>",
          "A <mark style=\"background-color:rgba\\(0, 0, 0, 0\\);color:\\#c22a2a\" class=\"has-inline-color\">balcony with plants</mark>",
          "<strong>What I desire</strong>",
          "Attending <strong>conferences</strong> and meeting plenty of <strong>interesting folks</strong> from the community",
          "To be able to focus on the important things of life"
        ],
        "to": [
          "$1<strong>Lo que me gusta</strong>$2",
          "$1Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️$2",
          "$1Montando mi bicicleta en la montaña$2",
          "$1<strong>Lo que tengo</strong>$2",
          "$1No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a>$2",
          "$1Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark>$2",
          "$1<strong>Lo que deseo</strong>$2",
          "$1Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad$2",
          "$1Ser capaz de concentrarse en las cosas importantes de la vida.$2"
        ]
      },
      "coreListItemContent": {
        "from": [
          "Eggplant",
          "Zucchini",
          "Pumpkin",
          "Broccoli",
          "Vegetarian",
          "Chinese",
          "Indian",
          "Thai",
          "Pizza"
        ],
        "to": [
          "$1Berenjena$2",
          "$1Calabacín$2",
          "$1Calabaza$2",
          "$1Brócoli$2",
          "$1Vegetariano$2",
          "$1Chino$2",
          "$1indio$2",
          "$1tailandés$2",
          "$1Pizza$2"
        ]
      },
      "coreCoverAlt": {
        "from": [
          "Dog not caring about anything"
        ],
        "to": [
          "$1Perro sin importarle nada$2"
        ]
      },
      "coreMediaTextAlt": {
        "from": [
          "In red are the entities with a transformed name"
        ],
        "to": [
          "$1En rojo están las entidades con nombre transformado$2"
        ]
      },
      "coreVerseContent": {
        "from": [
          "Reality is created by the mind, <strong>we can change our reality by changing our mind</strong>\\.",
          "Nothing exists except atoms and empty space; everything else is opinion\\.<br><br>The man enslaved to wealth can never be honest\\.<br><br>–Democritus"
        ],
        "to": [
          "$1La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.$2",
          "$1Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito$2"
        ]
      },
      "coreQuoteCitation": {
        "from": [
          "Victor Hugo \\(French Romantic writer and politician\\)"
        ],
        "to": [
          "$1Victor Hugo (escritor y político romántico francés)$2"
        ]
      },
      "corePullquoteCitation": {
        "from": [
          "Immanuel Kant \\(German philosopher and one of the central Enlightenment thinkers\\)"
        ],
        "to": [
          "$1Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)$2"
        ]
      },
      "corePullquoteValue": {
        "from": [
          "You only know me as you see me, not as I actually am\\."
        ],
        "to": [
          "$1Solo me conoces como me ves, no como soy en realidad.$2"
        ]
      },
      "coreAudioCaption": {
        "from": [
          "\"Broke for free\" song"
        ],
        "to": [
          "$1Canción \"Broke for free\"$2"
        ]
      },
      "coreVideoCaption": {
        "from": [
          "Test video of a road in a city"
        ],
        "to": [
          "$1Vídeo de prueba de una carretera en una ciudad$2"
        ]
      },
      "corePreformattedContent": {
        "from": [
          "Be a free thinker and don't accept everything you hear as truth\\. Be critical and evaluate what you believe in\\."
        ],
        "to": [
          "$1Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees.$2"
        ]
      },
      "coreEmbedCaption": {
        "from": [
          "At this year's WCEU Keynote address in Athens, Greece\\. <a href=\"https://www\\.youtube\\.com/hashtag/wordpress\">\\#WordPress</a> co-founder, Matt Mullenweg, Gutenberg Architect, Matías Ventura, and Executive Director, Josepha Haden Chomphosy reflect on WordPress in 2023 and aspirations for the coming year\\."
        ],
        "to": [
          "$1En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año.$2"
        ]
      }
    },
    "regexReplacements": {
      "meta": {
        "from": [
          ""
        ],
        "to": [
          "¡Bienvenidos a un solo post lleno de bloques!"
        ]
      },
      "coreHeadingContent": {
        "from": [
          "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)This blog post will be transformed\\.\\.\\.(</h[1-6]>\\n?<!-- /wp:heading -->)#",
          "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)<mark style=\"background-color:\\#D1D1E4\" class=\"has-inline-color\">I love these veggies!!!</mark>(</h[1-6]>\\n?<!-- /wp:heading -->)#",
          "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)When going to eat out, I normally go for one of these:(</h[1-6]>\\n?<!-- /wp:heading -->)#",
          "#(<!-- wp:heading .*?-->\\n?<h[1-6] ?.*?>)This heading \\(H3\\) is boring \\(Regex test: \\$1 \\#1\\), but these guys are not(</h[1-6]>\\n?<!-- /wp:heading -->)#"
        ],
        "to": [
          "$1Esta entrada de blog se transformará...$2",
          "$1<mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark>$2",
          "$1Cuando voy a comer fuera, normalmente elijo uno de estos:$2",
          "$1Este encabezado (H3) es aburrido (prueba Regex: \\$1 #1), pero estos tipos no lo son$2"
        ]
      },
      "coreParagraphContent": {
        "from": [
          "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds\\. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>\\. \\(<a href=\"https://www\\.azquotes\\.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Quote by Fyodor Dostoevsky</a>\\)<br>(</p>\\n?<!-- /wp:paragraph -->)#",
          "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life\\. \\(<a href=\"https://www\\.azquotes\\.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Quote by Leo Tolstoy</a>\\)<br>(</p>\\n?<!-- /wp:paragraph -->)#",
          "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)See if I care(</p>\\n?<!-- /wp:paragraph -->)#",
          "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)If you want to know who controls you, look at who you are not allowed to criticize\\. \\(<a rel=\"noreferrer noopener\" href=\"https://www\\.azquotes\\.com/author/15138-Voltaire\" target=\"_blank\">Quote by Voltaire</a>\\)(</p>\\n?<!-- /wp:paragraph -->)#",
          "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)This is the schema(</p>\\n?<!-- /wp:paragraph -->)#",
          "#(<!-- wp:paragraph .*?-->\\n?<p ?.*?>)Our mind is enriched by what we receive, our heart by what we give\\.(</p>\\n?<!-- /wp:paragraph -->)#"
        ],
        "to": [
          "$1Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br>$2",
          "$1Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br>$2",
          "$1Mira si me importa$2",
          "$1Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)$2",
          "$1este es el esquema$2",
          "$1Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos.$2"
        ]
      },
      "coreImageAlt": {
        "from": [
          "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")Ooops, I got stuck(\\\".*>.*\\n?<!-- /wp:image -->)#",
          "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")Funny dog in couch(\\\".*>.*\\n?<!-- /wp:image -->)#",
          "#(<!-- wp:image .*?-->\\n?.*<img .*?alt=\\\")Even funnier dog(\\\".*>.*\\n?<!-- /wp:image -->)#"
        ],
        "to": [
          "$1Vaya, me quedé atascado$2",
          "$1Perro divertido en el sofá$2",
          "$1Perro aún más divertido$2"
        ]
      },
      "coreImageCaption": {
        "from": [
          "#(<!-- wp:image .*?-->\\n?.*<figcaption ?.*?>)My owner doesn't give me a hat, so I gotta do a hack(</figcaption>.*\\n?<!-- /wp:image -->)#",
          "#(<!-- wp:image .*?-->\\n?.*<figcaption ?.*?>)Bring me the chips please(</figcaption>.*\\n?<!-- /wp:image -->)#",
          "#(<!-- wp:image .*?-->\\n?.*<figcaption ?.*?>)Anyone having chips\\? Don't leave me alone!(</figcaption>.*\\n?<!-- /wp:image -->)#"
        ],
        "to": [
          "$1Mi dueño no me da un sombrero, así que tengo que hacer un truco.$2",
          "$1Tráeme las papas por favor$2",
          "$1¿Alguien tiene fichas? ¡No me dejes solo!$2"
        ]
      },
      "coreButtonText": {
        "from": [
          "#(<!-- wp:button .*?-->\\n?.*<a ?.*?>)Do some duck duck search online(</a>.*\\n?<!-- /wp:button -->)#",
          "#(<!-- wp:button .*?-->\\n?.*<a ?.*?>)Surprise your mum with a phone call 🤙(</a>.*\\n?<!-- /wp:button -->)#",
          "#(<!-- wp:button .*?-->\\n?.*<a ?.*?>)Start a conversation with a stranger 😜(</a>.*\\n?<!-- /wp:button -->)#",
          "#(<!-- wp:button .*?-->\\n?.*<a ?.*?>)Keep your soul satiated 🙏(</a>.*\\n?<!-- /wp:button -->)#"
        ],
        "to": [
          "$1Haga una búsqueda de pato pato en línea$2",
          "$1Sorprende a tu mamá con una llamada telefónica 🤙$2",
          "$1Inicia una conversación con un extraño 😜$2",
          "$1Mantén tu alma saciada 🙏$2"
        ]
      },
      "coreTableCaption": {
        "from": [
          "#(<!-- wp:table .*?-->\\n?.*<figcaption ?.*?>.*)Some items to consider for new year's resolution\\?(.*</figcaption>.*\\n?<!-- /wp:table -->)#"
        ],
        "to": [
          "$1¿Algunos elementos a considerar para la resolución de año nuevo?$2"
        ]
      },
      "coreTableBodyCellsContent": {
        "from": [
          "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)<strong>What I like</strong>(.*</table>.*\\n?<!-- /wp:table -->)#",
          "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)Petting my 3 cats 😺😺😺 in the morning ☀️(.*</table>.*\\n?<!-- /wp:table -->)#",
          "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)Riding my bike in the mountain(.*</table>.*\\n?<!-- /wp:table -->)#",
          "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)<strong>What I have</strong>(.*</table>.*\\n?<!-- /wp:table -->)#",
          "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)Not much, just enough to <a href=\"https://keepmegoing\\.com\">keep me going on</a>(.*</table>.*\\n?<!-- /wp:table -->)#",
          "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)A <mark style=\"background-color:rgba\\(0, 0, 0, 0\\);color:\\#c22a2a\" class=\"has-inline-color\">balcony with plants</mark>(.*</table>.*\\n?<!-- /wp:table -->)#",
          "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)<strong>What I desire</strong>(.*</table>.*\\n?<!-- /wp:table -->)#",
          "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)Attending <strong>conferences</strong> and meeting plenty of <strong>interesting folks</strong> from the community(.*</table>.*\\n?<!-- /wp:table -->)#",
          "#(<!-- wp:table .*?-->\\n?.*<table ?.*?>.*)To be able to focus on the important things of life(.*</table>.*\\n?<!-- /wp:table -->)#"
        ],
        "to": [
          "$1<strong>Lo que me gusta</strong>$2",
          "$1Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️$2",
          "$1Montando mi bicicleta en la montaña$2",
          "$1<strong>Lo que tengo</strong>$2",
          "$1No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a>$2",
          "$1Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark>$2",
          "$1<strong>Lo que deseo</strong>$2",
          "$1Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad$2",
          "$1Ser capaz de concentrarse en las cosas importantes de la vida.$2"
        ]
      },
      "coreListItemContent": {
        "from": [
          "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Eggplant(</li>\\n?<!-- /wp:list-item -->)#",
          "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Zucchini(</li>\\n?<!-- /wp:list-item -->)#",
          "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Pumpkin(</li>\\n?<!-- /wp:list-item -->)#",
          "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Broccoli(</li>\\n?<!-- /wp:list-item -->)#",
          "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Vegetarian(</li>\\n?<!-- /wp:list-item -->)#",
          "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Chinese(</li>\\n?<!-- /wp:list-item -->)#",
          "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Indian(</li>\\n?<!-- /wp:list-item -->)#",
          "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Thai(</li>\\n?<!-- /wp:list-item -->)#",
          "#(<!-- wp:list-item .*?-->\\n?<li ?.*?>)Pizza(</li>\\n?<!-- /wp:list-item -->)#"
        ],
        "to": [
          "$1Berenjena$2",
          "$1Calabacín$2",
          "$1Calabaza$2",
          "$1Brócoli$2",
          "$1Vegetariano$2",
          "$1Chino$2",
          "$1indio$2",
          "$1tailandés$2",
          "$1Pizza$2"
        ]
      },
      "coreCoverAlt": {
        "from": [
          "#(<!-- wp:cover .*?-->\\n?.*<img .*?alt=\\\")Dog not caring about anything(\\\".*>.*\\n?<!-- /wp:cover -->)#"
        ],
        "to": [
          "$1Perro sin importarle nada$2"
        ]
      },
      "coreMediaTextAlt": {
        "from": [
          "#(<!-- wp:media-text .*?-->\\n?<div .*><figure .*><img .*?alt=\\\")In red are the entities with a transformed name(\\\")#"
        ],
        "to": [
          "$1En rojo están las entidades con nombre transformado$2"
        ]
      },
      "coreVerseContent": {
        "from": [
          "#(<!-- wp:verse .*?-->\\n?<pre ?.*?>)Reality is created by the mind, <strong>we can change our reality by changing our mind</strong>\\.(</pre>\\n?<!-- /wp:verse -->)#",
          "#(<!-- wp:verse .*?-->\\n?<pre ?.*?>)Nothing exists except atoms and empty space; everything else is opinion\\.<br><br>The man enslaved to wealth can never be honest\\.<br><br>–Democritus(</pre>\\n?<!-- /wp:verse -->)#"
        ],
        "to": [
          "$1La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.$2",
          "$1Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito$2"
        ]
      },
      "coreQuoteCitation": {
        "from": [
          "#(<!-- wp:quote .*?-->\\n?<blockquote ?.*?>.*<cite ?.*?>)Victor Hugo \\(French Romantic writer and politician\\)(</cite></blockquote>\\n?<!-- /wp:quote -->)#s"
        ],
        "to": [
          "$1Victor Hugo (escritor y político romántico francés)$2"
        ]
      },
      "corePullquoteCitation": {
        "from": [
          "#(<!-- wp:pullquote .*?-->\\n?<figure ?.*?><blockquote ?.*?><p ?.*?>.*</p><cite ?.*?>)Immanuel Kant \\(German philosopher and one of the central Enlightenment thinkers\\)(</cite></blockquote></figure>\\n?<!-- /wp:pullquote -->)#"
        ],
        "to": [
          "$1Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)$2"
        ]
      },
      "corePullquoteValue": {
        "from": [
          "#(<!-- wp:pullquote .*?-->\\n?<figure ?.*?><blockquote ?.*?><p ?.*?>)You only know me as you see me, not as I actually am\\.(</p><cite ?.*?>.*</cite></blockquote></figure>\\n?<!-- /wp:pullquote -->)#"
        ],
        "to": [
          "$1Solo me conoces como me ves, no como soy en realidad.$2"
        ]
      },
      "coreAudioCaption": {
        "from": [
          "#(<!-- wp:audio .*?-->\\n?<figure ?.*?><audio ?.*?>.*</audio><figcaption ?.*?>)\"Broke for free\" song(</figcaption></figure>\\n?<!-- /wp:audio -->)#"
        ],
        "to": [
          "$1Canción \"Broke for free\"$2"
        ]
      },
      "coreVideoCaption": {
        "from": [
          "#(<!-- wp:video .*?-->\\n?<figure ?.*?><video ?.*?>.*</video><figcaption ?.*?>)Test video of a road in a city(</figcaption></figure>\\n?<!-- /wp:video -->)#"
        ],
        "to": [
          "$1Vídeo de prueba de una carretera en una ciudad$2"
        ]
      },
      "corePreformattedContent": {
        "from": [
          "#(<!-- wp:preformatted .*?-->\\n?<pre ?.*?>)Be a free thinker and don't accept everything you hear as truth\\. Be critical and evaluate what you believe in\\.(</pre>\\n?<!-- /wp:preformatted -->)#"
        ],
        "to": [
          "$1Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees.$2"
        ]
      },
      "coreEmbedCaption": {
        "from": [
          "#(<!-- wp:embed .*?-->\\n?<figure ?.*?><div ?.*?>.*</div><figcaption ?.*?>)At this year's WCEU Keynote address in Athens, Greece\\. <a href=\"https://www\\.youtube\\.com/hashtag/wordpress\">\\#WordPress</a> co-founder, Matt Mullenweg, Gutenberg Architect, Matías Ventura, and Executive Director, Josepha Haden Chomphosy reflect on WordPress in 2023 and aspirations for the coming year\\.(</figcaption></figure>\\n?<!-- /wp:embed -->)#s"
        ],
        "to": [
          "$1En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año.$2"
        ]
      }
    },
    "transformedRawContent": "<!-- wp:paragraph -->\n<p>Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Esta entrada de blog se transformará...</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"style\":{\"color\":{\"text\":\"#ffffff\",\"background\":\"#709372\"}}} -->\n<p class=\"has-text-color has-background\" style=\"color:#ffffff;background-color:#709372\">Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:separator {\"opacity\":\"css\"} -->\n<hr class=\"wp-block-separator has-css-opacity\"/>\n<!-- /wp:separator -->\n\n<!-- wp:image {\"align\":\"wide\",\"id\":33} -->\n<figure class=\"wp-block-image alignwide\"><img src=\"https://i.ytimg.com/vi/z4KKd2nGlEM/maxresdefault.jpg\" alt=\"Vaya, me quedé atascado\" class=\"wp-image-33\"/><figcaption class=\"wp-element-caption\">Mi dueño no me da un sombrero, así que tengo que hacer un truco.</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\"><mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark></h3>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><!-- wp:list-item -->\n<li>Berenjena</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabacín</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabaza</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Brócoli</li>\n<!-- /wp:list-item --></ul>\n<!-- /wp:list -->\n\n<!-- wp:cover {\"url\":\"https://gatographql-pro.lndo.site/wp-content/uploads/2023/06/Funny-Dog.jpg\",\"id\":1080,\"dimRatio\":50,\"isDark\":false} -->\n<div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim\"></span><img class=\"wp-block-cover__image-background wp-image-1080\" alt=\"Dog not caring about anything\" src=\"https://gatographql-pro.lndo.site/wp-content/uploads/2023/06/Funny-Dog.jpg\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title…\",\"fontSize\":\"large\"} -->\n<p class=\"has-text-align-center has-large-font-size\">Mira si me importa</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:cover -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Cuando voy a comer fuera, normalmente elijo uno de estos:</h3>\n<!-- /wp:heading -->\n\n<!-- wp:list {\"ordered\":true} -->\n<ol><!-- wp:list-item -->\n<li>Vegetariano</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Chino</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>indio</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>tailandés</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Pizza</li>\n<!-- /wp:list-item --></ol>\n<!-- /wp:list -->\n\n<!-- wp:columns {\"align\":\"wide\"} -->\n<div class=\"wp-block-columns alignwide\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph -->\n<p>Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:media-text {\"mediaId\":362,\"mediaLink\":\"https://gatographql.lndo.site/graphql-api-search-results/\",\"mediaType\":\"image\"} -->\n<div class=\"wp-block-media-text alignwide is-stacked-on-mobile\"><figure class=\"wp-block-media-text__media\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-suki-1024x598.png\" alt=\"En rojo están las entidades con nombre transformado\" class=\"wp-image-362 size-full\"/></figure><div class=\"wp-block-media-text__content\"><!-- wp:paragraph {\"placeholder\":\"Content…\"} -->\n<p>este es el esquema</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:media-text --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:verse -->\n<pre class=\"wp-block-verse\">La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.</pre>\n<!-- /wp:verse --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:button {\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link wp-element-button\" href=\"https://duckduckgo.com/\">Haga una búsqueda de pato pato en línea</a></div>\n<!-- /wp:button -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Este encabezado (H3) es aburrido (prueba Regex: $1 #1), pero estos tipos no lo son</h3>\n<!-- /wp:heading -->\n\n<!-- wp:gallery {\"columns\":3,\"linkTo\":\"none\",\"align\":\"wide\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery alignwide has-nested-images columns-3 is-cropped alignnone\"><!-- wp:image {\"id\":32,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.insider.com/5f44388342f43f001ddfec52\" alt=\"Perro divertido en el sofá\" class=\"wp-image-32\"/><figcaption class=\"wp-element-caption\">Tráeme las papas por favor</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"id\":31,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.pinimg.com/originals/85/a7/f5/85a7f5cee4c93cb3995d1b51e3a0289f.jpg\" alt=\"Perro aún más divertido\" class=\"wp-image-31\"/><figcaption class=\"wp-element-caption\">¿Alguien tiene fichas? ¡No me dejes solo!</figcaption></figure>\n<!-- /wp:image --></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos.</p>\n<!-- /wp:paragraph --><cite>Victor Hugo (escritor y político romántico francés)</cite></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:buttons {\"align\":\"wide\",\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\"}} -->\n<div class=\"wp-block-buttons alignwide\"><!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\">Sorprende a tu mamá con una llamada telefónica 🤙</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\">Inicia una conversación con un extraño 😜</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|60\",\"right\":\"var:preset|spacing|60\",\"bottom\":\"var:preset|spacing|60\",\"left\":\"var:preset|spacing|60\"}},\"color\":{\"background\":\"#382998\"}},\"fontSize\":\"extra-large\"} -->\n<div class=\"wp-block-button has-custom-font-size has-extra-large-font-size\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background-color:#382998;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)\">Mantén tu alma saciada 🙏</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n<!-- wp:pullquote -->\n<figure class=\"wp-block-pullquote\"><blockquote><p>Solo me conoces como me ves, no como soy en realidad.</p><cite>Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)</cite></blockquote></figure>\n<!-- /wp:pullquote -->\n\n<!-- wp:audio -->\n<figure class=\"wp-block-audio\"><audio controls src=\"https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3\"></audio><figcaption class=\"wp-element-caption\">Canción \"Broke for free\"</figcaption></figure>\n<!-- /wp:audio -->\n\n<!-- wp:video {\"id\":132,\"tracks\":[]} -->\n<figure class=\"wp-block-video\"><video controls src=\"https://download.samplelib.com/mp4/sample-5s.mp4\"></video><figcaption class=\"wp-element-caption\">Vídeo de prueba de una carretera en una ciudad</figcaption></figure>\n<!-- /wp:video -->\n\n<!-- wp:preformatted -->\n<pre class=\"wp-block-preformatted\">Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees.</pre>\n<!-- /wp:preformatted -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito</pre>\n<!-- /wp:verse -->\n\n<!-- wp:table {\"hasFixedLayout\":true,\"align\":\"wide\",\"className\":\"has-fixed-layout\"} -->\n<figure class=\"wp-block-table alignwide has-fixed-layout\"><table class=\"has-fixed-layout\"><tbody><tr><td><strong>Lo que me gusta</strong></td><td>Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️</td><td>Montando mi bicicleta en la montaña</td></tr><tr><td><strong>Lo que tengo</strong></td><td>No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a></td><td>Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark></td></tr><tr><td><strong>Lo que deseo</strong></td><td>Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad</td><td>Ser capaz de concentrarse en las cosas importantes de la vida.</td></tr></tbody></table><figcaption class=\"wp-element-caption\">¿Algunos elementos a considerar para la resolución de año nuevo?</figcaption></figure>\n<!-- /wp:table -->\n\n<!-- wp:embed {\"url\":\"https://www.youtube.com/watch?v=7Nmz3IjtPh0\",\"type\":\"video\",\"providerNameSlug\":\"youtube\",\"responsive\":true,\"className\":\"wp-embed-aspect-16-9 wp-has-aspect-ratio\"} -->\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\nhttps://www.youtube.com/watch?v=7Nmz3IjtPh0\n</div><figcaption class=\"wp-element-caption\">En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año.</figcaption></figure>\n<!-- /wp:embed -->",
    "transformedMeta": [
      "¡Bienvenidos a un solo post lleno de bloques!"
    ],
    "updatePost": {
      "status": "SUCCESS",
      "errors": null,
      "post": {
        "id": 40,
        "title": "¡Bienvenidos a un solo post lleno de bloques!",
        "rawContent": "<!-- wp:paragraph -->\n<p>Cuando miro hacia atrás en mi pasado y pienso cuánto tiempo perdí en nada, cuánto tiempo se ha perdido en futilidades, errores, perezas, incapacidad para vivir; qué poco lo aprecié, cuántas veces pequé contra mi corazón y mi alma, entonces mi corazón sangra. <strong>La vida es un regalo, la vida es felicidad, cada minuto puede ser una eternidad de felicidad</strong>. (<a href=\"https://www.azquotes.com/author/4085-Fyodor_Dostoevsky\" target=\"_blank\" rel=\"noopener\">Cita de Fyodor Dostoevsky</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Esta entrada de blog se transformará...</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"style\":{\"color\":{\"text\":\"#ffffff\",\"background\":\"#709372\"}}} -->\n<p class=\"has-text-color has-background\" style=\"color:#ffffff;background-color:#709372\">Si adquieres el hábito de no culpar a los demás, sentirás el crecimiento de la capacidad de amar en tu alma y verás crecer la bondad en tu vida. (<a href=\"https://www.azquotes.com/author/14706-Leo_Tolstoy\" target=\"_blank\" rel=\"noopener\">Cita de León Tolstoi</a>)<br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:separator {\"opacity\":\"css\"} -->\n<hr class=\"wp-block-separator has-css-opacity\"/>\n<!-- /wp:separator -->\n\n<!-- wp:image {\"align\":\"wide\",\"id\":33} -->\n<figure class=\"wp-block-image alignwide\"><img src=\"https://i.ytimg.com/vi/z4KKd2nGlEM/maxresdefault.jpg\" alt=\"Vaya, me quedé atascado\" class=\"wp-image-33\"/><figcaption class=\"wp-element-caption\">Mi dueño no me da un sombrero, así que tengo que hacer un truco.</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\"><mark style=\"background-color:#D1D1E4\" class=\"has-inline-color\">¡¡Me encantan estas verduras!!!</mark></h3>\n<!-- /wp:heading -->\n\n<!-- wp:list -->\n<ul><!-- wp:list-item -->\n<li>Berenjena</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabacín</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Calabaza</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Brócoli</li>\n<!-- /wp:list-item --></ul>\n<!-- /wp:list -->\n\n<!-- wp:cover {\"url\":\"https://gatographql-pro.lndo.site/wp-content/uploads/2023/06/Funny-Dog.jpg\",\"id\":1080,\"dimRatio\":50,\"isDark\":false} -->\n<div class=\"wp-block-cover is-light\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim\"></span><img class=\"wp-block-cover__image-background wp-image-1080\" alt=\"Dog not caring about anything\" src=\"https://gatographql-pro.lndo.site/wp-content/uploads/2023/06/Funny-Dog.jpg\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title…\",\"fontSize\":\"large\"} -->\n<p class=\"has-text-align-center has-large-font-size\">Mira si me importa</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:cover -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Cuando voy a comer fuera, normalmente elijo uno de estos:</h3>\n<!-- /wp:heading -->\n\n<!-- wp:list {\"ordered\":true} -->\n<ol><!-- wp:list-item -->\n<li>Vegetariano</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Chino</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>indio</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>tailandés</li>\n<!-- /wp:list-item -->\n\n<!-- wp:list-item -->\n<li>Pizza</li>\n<!-- /wp:list-item --></ol>\n<!-- /wp:list -->\n\n<!-- wp:columns {\"align\":\"wide\"} -->\n<div class=\"wp-block-columns alignwide\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:paragraph -->\n<p>Si quieres saber quién te controla, mira a quién no puedes criticar. (<a rel=\"noreferrer noopener\" href=\"https://www.azquotes.com/author/15138-Voltaire\" target=\"_blank\">Cita de Voltaire</a>)</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:media-text {\"mediaId\":362,\"mediaLink\":\"https://gatographql.lndo.site/graphql-api-search-results/\",\"mediaType\":\"image\"} -->\n<div class=\"wp-block-media-text alignwide is-stacked-on-mobile\"><figure class=\"wp-block-media-text__media\"><img src=\"https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-suki-1024x598.png\" alt=\"En rojo están las entidades con nombre transformado\" class=\"wp-image-362 size-full\"/></figure><div class=\"wp-block-media-text__content\"><!-- wp:paragraph {\"placeholder\":\"Content…\"} -->\n<p>este es el esquema</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:media-text --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:verse -->\n<pre class=\"wp-block-verse\">La realidad es creada por la mente, <strong>podemos cambiar nuestra realidad cambiando nuestra mente</strong>.</pre>\n<!-- /wp:verse --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:button {\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link wp-element-button\" href=\"https://duckduckgo.com/\">Haga una búsqueda de pato pato en línea</a></div>\n<!-- /wp:button -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">Este encabezado (H3) es aburrido (prueba Regex: $1 #1), pero estos tipos no lo son</h3>\n<!-- /wp:heading -->\n\n<!-- wp:gallery {\"columns\":3,\"linkTo\":\"none\",\"align\":\"wide\",\"className\":\"alignnone\"} -->\n<figure class=\"wp-block-gallery alignwide has-nested-images columns-3 is-cropped alignnone\"><!-- wp:image {\"id\":32,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.insider.com/5f44388342f43f001ddfec52\" alt=\"Perro divertido en el sofá\" class=\"wp-image-32\"/><figcaption class=\"wp-element-caption\">Tráeme las papas por favor</figcaption></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"id\":31,\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image\"><img src=\"https://i.pinimg.com/originals/85/a7/f5/85a7f5cee4c93cb3995d1b51e3a0289f.jpg\" alt=\"Perro aún más divertido\" class=\"wp-image-31\"/><figcaption class=\"wp-element-caption\">¿Alguien tiene fichas? ¡No me dejes solo!</figcaption></figure>\n<!-- /wp:image --></figure>\n<!-- /wp:gallery -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>Nuestra mente se enriquece con lo que recibimos, nuestro corazón con lo que damos.</p>\n<!-- /wp:paragraph --><cite>Victor Hugo (escritor y político romántico francés)</cite></blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:buttons {\"align\":\"wide\",\"layout\":{\"type\":\"flex\",\"justifyContent\":\"center\"}} -->\n<div class=\"wp-block-buttons alignwide\"><!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(89,51,82) 53%,rgb(155,81,224) 100%)\">Sorprende a tu mamá con una llamada telefónica 🤙</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background:linear-gradient(135deg,rgb(6,147,227) 0%,rgb(159,45,31) 81%,rgb(155,81,224) 100%)\">Inicia una conversación con un extraño 😜</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"style\":{\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|60\",\"right\":\"var:preset|spacing|60\",\"bottom\":\"var:preset|spacing|60\",\"left\":\"var:preset|spacing|60\"}},\"color\":{\"background\":\"#382998\"}},\"fontSize\":\"extra-large\"} -->\n<div class=\"wp-block-button has-custom-font-size has-extra-large-font-size\"><a class=\"wp-block-button__link has-background wp-element-button\" style=\"background-color:#382998;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)\">Mantén tu alma saciada 🙏</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n<!-- wp:pullquote -->\n<figure class=\"wp-block-pullquote\"><blockquote><p>Solo me conoces como me ves, no como soy en realidad.</p><cite>Immanuel Kant (filósofo alemán y uno de los pensadores centrales de la Ilustración)</cite></blockquote></figure>\n<!-- /wp:pullquote -->\n\n<!-- wp:audio -->\n<figure class=\"wp-block-audio\"><audio controls src=\"https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3\"></audio><figcaption class=\"wp-element-caption\">Canción \"Broke for free\"</figcaption></figure>\n<!-- /wp:audio -->\n\n<!-- wp:video {\"id\":132,\"tracks\":[]} -->\n<figure class=\"wp-block-video\"><video controls src=\"https://download.samplelib.com/mp4/sample-5s.mp4\"></video><figcaption class=\"wp-element-caption\">Vídeo de prueba de una carretera en una ciudad</figcaption></figure>\n<!-- /wp:video -->\n\n<!-- wp:preformatted -->\n<pre class=\"wp-block-preformatted\">Se un librepensador y no aceptes todo lo que escuchas como verdad. Sé crítico y evalúa aquello en lo que crees.</pre>\n<!-- /wp:preformatted -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Nada existe excepto los átomos y el espacio vacío; todo lo demás es opinión.<br><br>El hombre esclavizado por la riqueza nunca puede ser honesto.<br><br>–Demócrito</pre>\n<!-- /wp:verse -->\n\n<!-- wp:table {\"hasFixedLayout\":true,\"align\":\"wide\",\"className\":\"has-fixed-layout\"} -->\n<figure class=\"wp-block-table alignwide has-fixed-layout\"><table class=\"has-fixed-layout\"><tbody><tr><td><strong>Lo que me gusta</strong></td><td>Acariciando a mis 3 gatos 😺😺😺 en la mañana ☀️</td><td>Montando mi bicicleta en la montaña</td></tr><tr><td><strong>Lo que tengo</strong></td><td>No mucho, solo lo suficiente para <a href=\"https://keepmegoing.com\">mantenerme en marcha</a></td><td>Un <mark style=\"background-color:rgba(0, 0, 0, 0);color:#c22a2a\" class=\"has-inline-color\">balcón con plantas</mark></td></tr><tr><td><strong>Lo que deseo</strong></td><td>Asistir a <strong>conferencias</strong> y conocer a mucha <strong>gente interesante</strong> de la comunidad</td><td>Ser capaz de concentrarse en las cosas importantes de la vida.</td></tr></tbody></table><figcaption class=\"wp-element-caption\">¿Algunos elementos a considerar para la resolución de año nuevo?</figcaption></figure>\n<!-- /wp:table -->\n\n<!-- wp:embed {\"url\":\"https://www.youtube.com/watch?v=7Nmz3IjtPh0\",\"type\":\"video\",\"providerNameSlug\":\"youtube\",\"responsive\":true,\"className\":\"wp-embed-aspect-16-9 wp-has-aspect-ratio\"} -->\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\nhttps://www.youtube.com/watch?v=7Nmz3IjtPh0\n</div><figcaption class=\"wp-element-caption\">En el discurso de apertura de la WCEU de este año en Atenas, Grecia. El cofundador de <a href=\"https://www.youtube.com/hashtag/wordpress\">#WordPress</a>, Matt Mullenweg, el arquitecto de Gutenberg, Matías Ventura, y la directora ejecutiva, Josepha Haden Chomphosy, reflexionan sobre WordPress en 2023 y aspiraciones para el próximo año.</figcaption></figure>\n<!-- /wp:embed -->"
      }
    }
  }
}
```
