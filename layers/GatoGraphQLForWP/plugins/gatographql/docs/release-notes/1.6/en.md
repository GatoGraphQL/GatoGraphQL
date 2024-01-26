# Release Notes: 1.6

## Added

### Module Media Mutations

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

## Improvements

- Validate the license keys when updating the plugin
- Simplified the Tutorial section

## Fixed

- Bug where a syntax error on a variable definition in the GraphQL query was not validated