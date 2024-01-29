# Release Notes: 1.6

## Added

### Module "Media Mutations"

The new module "Media Mutations" adds fields to the schema that upload media attachments, and query for the logged-in user's media attachments.

### Mutation `createMediaItem`

Mutation `createMediaItem` allows uploading files to the Media Library. It offers 2 ways to provide the source file:

1. Via URL
2. Directly its contents

Running this query:

```graphql
mutation CreateMediaItems {
  fromURL: createMediaItem(input: {
    from: {
      url: {
        source: "https://gatographql.com/assets/GatoGraphQL-logo.png"
      }
    }
    caption: "Gato GraphQL logo"
    altText: "This is the Gato GraphQL logo"
  }) {
    mediaItemID
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    mediaItem {
      ...MediaItemData
    }
  }

  directlyByContents: createMediaItem(input: {
    from: {
      contents: {
        body: """
<html>
  <body>
    Hello world!
  </body>
</html>
        """
        filename: "hello-world.html"
      }
    }
    title: "Hello world!"
  }) {
    mediaItemID
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    mediaItem {
      ...MediaItemData
    }
  }
}

fragment MediaItemData on Media {
  altText
  caption
  mimeType
  slug
  src
  title
}
```

...will produce:

```json
{
  "data": {
    "fromURL": {
      "mediaItemID": 1380,
      "status": "SUCCESS",
      "errors": null,
      "mediaItem": {
        "altText": "This is the Gato GraphQL logo",
        "caption": "Gato GraphQL logo",
        "mimeType": "image/png",
        "slug": "gatographql-logo-png",
        "src": "https://mysite.com/wp-content/uploads/GatoGraphQL-logo.png",
        "title": "GatoGraphQL-logo.png"
      }
    },
    "directlyByContents": {
      "mediaItemID": 1381,
      "status": "SUCCESS",
      "errors": null,
      "mediaItem": {
        "altText": "",
        "caption": "",
        "mimeType": "text/html",
        "slug": "hello-world-html",
        "src": "https://mysite.com/wp-content/uploads/hello-world.html",
        "title": "Hello world!"
      }
    }
  }
}
```

### Fields `myMediaItemCount`, `myMediaItems` and `myMediaItem`

Logged-in users can now retrieve all of their media files.

Running this query:

```graphql
query GetMediaItems {
  me {
    slug
  }
  
  myMediaItemCount

  myMediaItems(pagination: {
    limit: 3
  }) {
    ...MediaItemData
  }

  myMediaItem(by: { id: 1380 }) {
    ...MediaItemData
  }
}

fragment MediaItemData on Media {
  id
  mimeType
  src
  author {
    slug
  }
}
```

...will produce:

```json
{
  "data": {
    "me": {
      "slug": "admin"
    },
    "myMediaItemCount": 2,
    "myMediaItems": [
      {
        "id": 1380,
        "mimeType": "image/png",
        "src": "https://mysite.com/wp-content/uploads/GatoGraphQL-logo.png",
        "author": {
          "slug": "admin"
        }
      },
      {
        "id": 1365,
        "mimeType": "image/png",
        "src": "https://mysite.com/wp-content/uploads/browser.png",
        "author": {
          "slug": "admin"
        }
      }
    ],
    "myMediaItem": {
      "id": 1380,
      "mimeType": "image/png",
      "src": "https://mysite.com/wp-content/uploads/GatoGraphQL-logo.png",
      "author": {
        "slug": "admin"
      }
    }
  }
}
```

### Predefined persisted query "Generate a post's featured image using AI and optimize it"

A new predefined Persisted query, with title "Generate a post's featured image using AI and optimize it", has been added.

It uses generative AI to produce images for posts without a featured image, choosing from these service providers:

- [OpenAI's DALL-E](https://openai.com/dall-e-3)
- [Stable Diffusion](https://stablediffusionapi.com/)

It first checks if a post has a featured image. If it does not, it creates one by calling the generative AI service. We must provide the corresponding API key for the chosen service to use.

As the generative AI images are not optimized for the web, the query can also send the newly-generated image to [TinyPNG](https://tinypng.com/) to compress it. We must provide the API key to use it.

Finally, the query creates a new media item with the image, and sets it as the post's featured image.

### Documentation for new field `_dataMatrixOutputAsCSV` from the Helper Function Collection extension

Field `_dataMatrixOutputAsCSV` has been added to the documentation for the Helper Function Collection extension.

This field takes a matrix of data, and produces a CSV string. For instance, this query:

```graphql
csv: _dataMatrixOutputAsCSV(
  fields: 
    ["Name", "Surname", "Year"]
  data: [
    ["John", "Smith", 2003],
    ["Pedro", "Gonzales", 2012],
    ["Manuel", "Perez", 2008],
    ["Jose", "Pereyra", 1999],
    ["Jacinto", "Bloomberg", 1998],
    ["Jun-E", "Song", 1983],
    ["Juan David", "Santamaria", 1943],
    ["Luis Miguel", null, 1966],
  ]
)
```

...will produce:

```json
{
  "data": {
    "csv": "Name,Surname,Year\nJohn,Smith,2003\nPedro,Gonzales,2012\nManuel,Perez,2008\nJose,Pereyra,1999\nJacinto,Bloomberg,1998\nJun-E,Song,1983\nJuan David,Santamaria,1943\nLuis Miguel,,1966\n"
  }
}
```

## Improvements

- Validate the license keys when updating the plugin
- Simplified the Tutorial section
- Prevent max execution time issues when installing plugin on (cheap) shared hosting (#2631)
  - Validate that the PHP memory limit is at least 64MB to load Gato GraphQL
  - Store the new plugin versions to DB only after generating the service container cache
  - Disable the max execution time when compiling the container

## Fixed

- Bug where a syntax error on a variable definition in the GraphQL query was not validated

## Breaking changes

### Field resolver's `validateFieldArgValue` method receives extra argument `$fieldArgs`

It is now possible for a field to validate an input based on the value of another input.

For this, method [`validateFieldArgValue` from `AbstractObjectTypeFieldResolver`](https://github.com/GatoGraphQL/GatoGraphQL/blob/949c63f9b163b106e517af69d62bdd8eb3dcbd73/layers/Engine/packages/component-model/src/FieldResolvers/ObjectType/AbstractObjectTypeFieldResolver.php#L649) now receives a `$fieldArgs` param, which contains the values for all inputs provided to the field:

```php
/**
 * Validate the constraints for a field argument
 * @param array<string,mixed> $fieldArgs
 */
public function validateFieldArgValue(
    ObjectTypeResolverInterface $objectTypeResolver,
    string $fieldName,
    string $fieldArgName,
    mixed $fieldArgValue,
    AstInterface $astNode,
    array $fieldArgs,
    ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
): void;
```
