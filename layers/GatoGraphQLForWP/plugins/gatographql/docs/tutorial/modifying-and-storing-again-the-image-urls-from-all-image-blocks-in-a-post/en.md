# Lesson 13: Modifying (and storing again) the image URLs from all Image blocks in a post

In the previous tutorial lesson, we learnt that we can iterate the inner structure of (Gutenberg) blocks in the post content, and extract desired items.

Let's now explore how to transform those items, and store them again in the post.

This lesson modifies the URL of images in the `core/image` blocks in a post:

- Replacing `mysite.com` to `cdn.mysite.com` (as to start serving those assets from a CDN)
- Replacing `.jpg` with `.avif`

## GraphQL query to transform (and store again) the image URLs from all `core/image` blocks in a post

Mutation `updatePost` receives the post's HTML content. Then, we must:

- Retrieve the post's `rawContent`
- Apply transformations to that HTML code, replacing the original URLs with the converted URLs
- Store the adapted content

The GraphQL query below executes the transformations in the HTML code via directive `@strRegexReplaceMultiple`, with each regex pattern generated dynamically based on the blocks' inner HTML content (in this case, it targets the `src` element in the `core/image` block's HTML code). Because the generated regex patterns could contain any of the regex special characters (such as `.`, `+`, `(`, etc), and the replacements could contain a regex replacement variable (such as `$1`), these must be escaped.

```graphql
query InitializeEmptyVariables {
  emptyArray: _echo(value: [])
    @export(as: "coreImageURLItems")
    @export(as: "coreImageURLReplacementsFrom")
    @export(as: "coreImageURLReplacementsTo")
    @remove
}

# Extract all the image URLs from the `core/image` blocks, and export them under `$coreImageURLItems`
query FetchData($postID: ID!)
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
  @depends(on: "InitializeEmptyVariables")
{
  post(by: { id: $postID } ) {
    rawContent
      @export(as: "rawContent")

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
              string: "${1}%s${2}",
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

# Execute the regex search and replace, export the results under `$transformedRawContent`
query ExecuteRegexReplacements
  @depends(on: "CreateRegexReplacements")
{  
  transformedRawContent: _echo(value: $rawContent)
    @strRegexReplaceMultiple(
      limit: 1,
      searchRegex: $coreImageURLReplacementsFrom,
      replaceWith: $coreImageURLReplacementsTo
    )
    
    @export(as: "transformedRawContent")
}

# Execute the mutation to update the post
mutation ModifyAndUpdatePost($postID: ID!)
  @depends(on: "ExecuteRegexReplacements")
{
  updatePost(input: {
    id: $postID,
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

The response is:

```json
{
  "data": {
    "post": {
      "rawContent": "<!-- wp:paragraph -->\n<p>This is a paragraph block.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Image Block (Standard)</h2>\n<!-- /wp:heading -->\n\n<!-- wp:image {\"sizeSlug\":\"large\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"http://mysite.com/fs_img/mysite/2008/themes_photo.jpg\" alt=\"\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"sizeSlug\":\"large\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"http://mysite.com/fs_img/mysite/2007/email_photo.jpg\" alt=\"\"/></figure>\n<!-- /wp:image -->",
      "coreImage": [
        {
          "name": "core/image",
          "attributes": {
            "sizeSlug": "large",
            "url": "http://mysite.com/fs_img/mysite/2008/themes_photo.jpg",
            "alt": ""
          }
        },
        {
          "name": "core/image",
          "attributes": {
            "sizeSlug": "large",
            "url": "http://mysite.com/fs_img/mysite/2007/email_photo.jpg",
            "alt": ""
          }
        }
      ]
    },
    "transformations": {
      "coreImageURL": {
        "from": [
          "http://mysite.com/fs_img/mysite/2008/themes_photo.jpg",
          "http://mysite.com/fs_img/mysite/2007/email_photo.jpg"
        ],
        "to": [
          "https://cdn.mysite.com/fs_img/mysite/2008/themes_photo.avif",
          "https://cdn.mysite.com/fs_img/mysite/2007/email_photo.avif"
        ]
      }
    },
    "escapedRegexStrings": {
      "coreImageURL": {
        "from": [
          "http://mysite\\.com/fs_img/mysite/2008/themes_photo\\.jpg",
          "http://mysite\\.com/fs_img/mysite/2007/email_photo\\.jpg"
        ],
        "to": [
          "$1https://cdn.mysite.com/fs_img/mysite/2008/themes_photo.avif$2",
          "$1https://cdn.mysite.com/fs_img/mysite/2007/email_photo.avif$2"
        ]
      }
    },
    "regexReplacements": {
      "coreImageURL": {
        "from": [
          "#(<!-- wp:image .*?-->\\n?.*<img .*?src=\\\")http://mysite\\.com/fs_img/mysite/2008/themes_photo\\.jpg(\\\".*>.*\\n?<!-- /wp:image -->)#",
          "#(<!-- wp:image .*?-->\\n?.*<img .*?src=\\\")http://mysite\\.com/fs_img/mysite/2007/email_photo\\.jpg(\\\".*>.*\\n?<!-- /wp:image -->)#"
        ],
        "to": [
          "$1https://cdn.mysite.com/fs_img/mysite/2008/themes_photo.avif$2",
          "$1https://cdn.mysite.com/fs_img/mysite/2007/email_photo.avif$2"
        ]
      }
    },
    "transformedRawContent": "<!-- wp:paragraph -->\n<p>This is a paragraph block.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Image Block (Standard)</h2>\n<!-- /wp:heading -->\n\n<!-- wp:image {\"sizeSlug\":\"large\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://cdn.mysite.com/fs_img/mysite/2008/themes_photo.avif\" alt=\"\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"sizeSlug\":\"large\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://cdn.mysite.com/fs_img/mysite/2007/email_photo.avif\" alt=\"\"/></figure>\n<!-- /wp:image -->",
    "updatePost": {
      "status": "SUCCESS",
      "errors": null,
      "post": {
        "id": 13,
        "title": "Released v0.6, check it out",
        "rawContent": "<!-- wp:paragraph -->\n<p>This is a paragraph block.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Image Block (Standard)</h2>\n<!-- /wp:heading -->\n\n<!-- wp:image {\"sizeSlug\":\"large\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://cdn.mysite.com/fs_img/mysite/2008/themes_photo.avif\" alt=\"\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:image {\"sizeSlug\":\"large\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://cdn.mysite.com/fs_img/mysite/2007/email_photo.avif\" alt=\"\"/></figure>\n<!-- /wp:image -->"
      }
    }
  }
}
```
