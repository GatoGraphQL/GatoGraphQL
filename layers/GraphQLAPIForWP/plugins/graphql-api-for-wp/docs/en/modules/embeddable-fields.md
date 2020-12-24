# Embeddable Fields

Resolve a field within an argument for another field from the same type, using syntax `{{ fieldName }}`, and also including arguments, using `{{ fieldName(fieldArgs) }}`.

It can also be used within directive arguments.

To make it convenient to use, field `echoStr(value: String): String` is also added on every type of the schema.

## When to use

Embedding fields offers several advantages:

- Fetching more data with a shorter query
- Avoiding manipulating the data in the client to give it its desired shape
- Executing functionality by configuration, through the [IFTTT module](https://github.com/GraphQLAPI/graphql-api-for-wp/issues/27)

## Examples

Compose a string containing the values from several fields:

```graphql
query {
  posts {
    description: echoStr(value: "'{{ title }}' was posted on {{ date }}")
  }
}
```

Change the title of the post, depending on the post having comments or not:

```graphql
query {
  posts {
    title: echoStr(value: "({{ commentCount }}) {{ title }} - posted on {{ date }}") @include(if: "{{ hasComments }}")
    title @skip(if: "{{ hasComments }}")
  }
}
```

Retrieve the posts containing the user's email:

```graphql
query {
  users {
    email
    posts(searchfor: "{{ email }}") {
      id
      title
      content
    }
  }
}
```

Send a a newsletter defining the `to`, `subject` and `content` data through fields `newsletterTo`, `newsletterSubject` and `newsletterContent` from the `Root` type:

```graphql
mutation {
  sendEmail(
    to: "{{ newsletterTo }}",
    subject: "{{ newsletterSubject }}",
    content: "{{ newsletterContent }}"
  )
}
```

Combined with nested mutations, send an email to several users, personalizing the content for each:

```graphql
mutation {
  users {
    sendEmail(
      subject: "Hey {{ name }}!",
      content: "What's up? Access to the service will expire soon!"
    )
  }
}
```

Format an embedded date to `"d/m/Y"` (the string quotes must be escaped as `\"`):

```graphql
mutation {
  users {
    sendEmail(
      subject: "Hey {{ name }}!",
      content: "What's up? You only have until {{ serviceExpirationDate(format: \"d/m/Y\") }} to renew the service."
    )
  }
}
```

Send a single email to many users, adding them all in the to field:

```graphql
mutation {
  users {
    id @sendEmail(to: "{{ email }}", subject: "Congratulations team!", content: "You have won the competition!")
  }
}
```

Combined with nested mutations and the flat chain syntax, compose the `content` of the email already in the query:

```graphql
mutation {
  comment(id: 1) {
    replyToComment(data: data) {
      id @sendEmail(
        to: "{{ parentComment.author.email }}",
        subject: "{{ author.name }} has replied to your comment",
        content: "
          <p>On {{ comment.date(format: \"d/m/Y\") }}, {{ author.name }} says:</p>
          <blockquote>{{ comment.content }}</blockquote>
          <p>Read online: {{ comment.url }}</p>
        "
      )
    }
  }
}
```

## GraphQL spec

This functionality is currently not part of the GraphQL spec, but a related one has been requested:

- <a href="https://github.com/graphql/graphql-spec/issues/682" target="_blank">Issue #682 - [RFC] Composable fields</a>
