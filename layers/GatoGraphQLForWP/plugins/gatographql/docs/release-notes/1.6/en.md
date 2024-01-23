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
mutation TestCreateMediaItemFromURL {
  fromURL: createMediaItem(input:{
    from: {
      url: "https://gatographql.com/assets/GatoGraphQL-logo.png"
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

  directlyByContents: createMediaItem(input:{
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

  myMediaItem(by: { id: 1082 }) {
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
```

## Improvements

- Validate the license keys when updating the plugin
- Simplified the Tutorial section
