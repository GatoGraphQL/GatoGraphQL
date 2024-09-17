# Multiple Query Execution

Multiple queries are combined together, and executed as a single operation, reusing their state and their data.

## Description

Multiple query execution combines the multiple queries into a single query, making sure they are executed in the same requested order. Operations can communicate state with each other via dynamic variables, which are computed only once but can be read multiple times throughout the document.

```graphql
query SomeQuery {
  id @export(as: $rootID)
}

query AnotherQuery
  @depends(on: "SomeQuery")
{
  _echo(value: $rootID )
}
```

This feature offers several benefits:

- It improves performance: Instead of executing a query against the GraphQL server, then wait for its response, and then use that result to execute another query, we can combine the queries together into one and execute them in a single request, thus avoiding the latency from the multiple HTTP connections.
- It allows us to manage our GraphQL queries into atomic operations (or logical units) that depend on each other, and that can be conditionally executed based on the result from a previous operation.

Multiple query execution is different from query batching, in which the GraphQL server also executes multiple queries in a single request, but those queries are merely executed one after the other, independently from each other.

## Enabled directives

When Multiple query execution is enabled, the following directives are made available in the GraphQL schema:

- `@depends` (operation directive): To have an operation (whether a `query` or `mutation`) indicate what other operations must be executed before
- `@export` (field directive): To export some field value from one query as a dynamic variable, to be input to some field or directive in another query
- `@deferredExport` (field directive): Similar to `@export` but to be used with **Multi-Field Directives**

In addition, directives `@include` and `@skip` are also made available as operation directives (they are normally only field directives), and these can be used to conditionally execute an operation if it satisfies some condition.

<!-- ## When to use

Let's suppose we want to search all posts which mention the name of the logged-in user. Normally, we would need 2 queries to accomplish this:

We first retrieve the user's `name`:

```graphql
query GetLoggedInUserName {
  me {
    name
  }
}
```

...and then, having executed the first query, we can pass the retrieved user's `name` as variable `$search` to perform the search in a second query:

```graphql
query GetPostsContainingString($search: String = "") {
  posts(filter: { search: $search }) {
    id
    title
  }
}
```

The `@export` directive exports the value from a field, and inject this value into a second field through a dynamic variable (whose name is defined under argument `as`), thus combining the 2 queries into 1:

```graphql
query GetLoggedInUserName {
  me {
    name @export(as: "search")
  }
}

query GetPostsContainingString @depends(on: "GetLoggedInUserName") {
  posts(filter: { search: $search }) {
    id
    title
  }
}
``` -->

## `@depends`

When the GraphQL document contains multiple operations, we indicate to the server which one to execute via URL param `?operationName=...`; otherwise, the last operation will be executed.

Starting from this initial operation, the server will collect all operations to execute, which are defined by adding directive `depends(on: [...])`, and execute them in the corresponding order respecting the dependencies.

Directive argument `operations` receives an array of operation names (`[String]`), or we can also provide a single operation name (`String`).

In this query, we pass `?operationName=Four`, and the executed operations (whether `query` or `mutation`) will be `["One", "Two", "Three", "Four"]`:

```graphql
mutation One {
  # Do something ...
}

mutation Two {
  # Do something ...
}

query Three @depends(on: ["One", "Two"]) {
  # Do something ...
}

query Four @depends(on: "Three") {
  # Do something ...
}
```

## `@export`

Directive `@export` exports the value of a field (or set of fields) into a dynamic variable, to be used as input in some field or query from another query.

For instance, in this query we export the logged-in user's name, and use this value to search for posts containing this string (please notice that variable `$loggedInUserName`, because it is dynamic, does not need be defined in operation `FindPosts`):

```graphql
query GetLoggedInUserName {
  me {
    name @export(as: $loggedInUserName)
  }
}

query FindPosts @depends(on: "GetLoggedInUserName") {
  posts(filter: { search: $loggedInUserName }) {
    id
  }
}
```

## `@deferredExport`

When the **Multi-Field Directives** feature is enabled and we export the value of multiple fields into a dictionary, use `@deferredExport` instead of `@export` to guarantee that all directives from each involved field have been executed before exporting the field's value.

For instance, in this query, the first field has directive `@strUpperCase` applied to it, and the second has `@strTitleCase`. When executing `@deferredExport`, the exported value will have these directives applied:

```graphql
query One {
  id @strUpperCase # Will be exported as "ROOT"
  again: id @strTitleCase # Will be exported as "Root"
    @deferredExport(as: "props", affectAdditionalFieldsUnderPos: [1])
}

query Two @depends(on: "One") {
  mirrorProps: _echo(value: $props)
}
```

Producing:

```json
{
  "data": {
    "id": "ROOT",
    "again": "Root",
    "mirrorProps": {
      "id": "ROOT",
      "again": "Root"
    }
  }
}
```

## `@skip` and `@include` (in operations)

When Multiple Query Execution is enabled, directives `@include` and `@skip` are also available as operation directives, and these can be used to conditionally execute an operation if it satisfies some condition.

For instance, in this query, operation `CheckIfPostExists` exports a dynamic variable `$postExists` and, only if its value is `true`, will mutation `ExecuteOnlyIfPostExists` be executed:

```graphql
query CheckIfPostExists($id: ID!) {
  # Initialize the dynamic variable to `false`
  postExists: _echo(value: false) @export(as: "postExists")

  post(by: { id: $id }) {
    # Found the Post => Set dynamic variable to `true`
    postExists: _echo(value: true) @export(as: "postExists")
  }
}

mutation ExecuteOnlyIfPostExists
  @depends(on: "CheckIfPostExists")
  @include(if: $postExists)
{
  # Do something...
}
```

## Dynamic variable outputs

`@export` can produce 6 different outputs, based on a combination of:

- The value of the `type` argument (either `SINGLE`, `LIST` or `DICTIONARY`)
- If the directive is applied to a single field, or to multiple fields (via the **Multi-Field Directives** module)

The 6 possible outputs then are:

1. `SINGLE` type:
    1. Single field
    2. Multi-field
2. `LIST` type:
    1. Single field
    2. Multi-field
3. `DICTIONARY` type:
    1. Single field
    2. Multi-field

### `SINGLE` type / Single field

The output is a single value when passing param `type: SINGLE` (which is set as the default value).

In this query:

```graphql
query {
  post(by: { id: 1 }) {
    title @export(as: "postTitle", type: SINGLE)
  }
}
```

...the dynamic variable `$postTitle` will have value:

```json
"Hello world!"
```

Please notice that if `SINGLE` is applied over an array of entities, then the value for the last entity is the one that is exported.

In this query:

```graphql
query {
  posts(filter: { ids: [1, 5] }) {
    title @export(as: "postTitle", type: SINGLE)
  }
}
```

...the dynamic variable `$postTitle` will have the value for post with ID `5`:

```json
"Everything good?"
```

### `SINGLE` type / Multi-field

If `@export` is applied on several fields (by adding param `affectAdditionalFieldsUnderPos` provided by the **Multi-Field Directives** module), then the value that is set on the dynamic variable is a dictionary of `{ key: field alias, value: field value }` (of type `JSONObject`).

This query:

```graphql
query {
  post(by: { id: 1 }) {
    title
    content
      @export(
        as: "postData",
        type: SINGLE,
        affectAdditionalFieldsUnderPos: [1]
      )
  }
}
```

...exports dynamic variable `$postData` with value:

```json
{
  "title": "Hello world!",
  "content": "Lorem ipsum."
}
```

### `LIST` type / Single field

The dynamic variable will contain an array with the field value from all the queried entities (from the enclosing field), by passing param `type: LIST`.

When running this query (in which queried entities are posts with ID `1` and `5`):

```graphql
query {
  posts(filter: { ids: [1, 5] }) {
    title @export(as: "postTitles", type: LIST)
  }
}
```

...the dynamic variable `$postTitles` will have value:

```json
[
  "Hello world!",
  "Everything good?"
]
```

### `LIST` type / Multi-field

We obtain an array of dictionaries (of type `JSONObject`), each containing the values of the fields on which the directive is applied.

This query:

```graphql
query {
  posts(filter: { ids: [1, 5] }) {
    title
    content
      @export(
        as: "postsData",
        type: LIST,
        affectAdditionalFieldsUnderPos: [1]
      )
  }
}
```

...exports dynamic variable `$postsData` with value:

```json
[
  {
    "title": "Hello world!",
    "content": "Lorem ipsum."
  },
  {
    "title": "Everything good?",
    "content": "Quisque convallis libero in sapien pharetra tincidunt."
  }
]
```

### `DICTIONARY` type / Single field

The dynamic variable will contain a dictionary (of type `JSONObject`) with the ID from the queries entity as key, and the field values as value, by passing param `type: DICTIONARY`.

This query:

```graphql
query {
  posts(filter: { ids: [1, 5] }) {
    title @export(as: "postIDTitles", type: DICTIONARY)
  }
}
```

...exports dynamic variable `$postIDTitles` with value:

```json
{
  "1": "Hello world!",
  "5": "Everything good?"
}
```

### `DICTIONARY` type / Multi-field

In this combination, we export a dictionary of dictionaries: `{ key: entity ID, value: { key: field alias, value: field value } }` (using a type `JSONObject` that will contain entries of type `JSONObject`).

This query:

```graphql
query {
  posts(filter: { ids: [1, 5] }) {
    title
    content
      @export(
        as: "postsIDProperties",
        type: DICTIONARY,
        affectAdditionalFieldsUnderPos: [1]
      )
  }
}
```

...exports dynamic variable `$postsIDProperties` with value:

```json
{
  "1": {
    "title": "Hello world!",
    "content": "Lorem ipsum."
  },
  "5": {
    "title": "Everything good?",
    "content": "Quisque convallis libero in sapien pharetra tincidunt."
  }
}
```

<!-- Directive `@export` handles these cases:

1. Exporting a single value from a single field
2. Exporting a list of values from a single field

In addition, when the **Multi-Field Directives** module is enabled, `@export` handles 2 additional cases:

3. Exporting a dictionary of values, containing several fields from the same object
4. Exporting a list of a dictionary of values, with each dictionary containing several fields from the same object

#### 1. Exporting a single value from a single field

`@export` must handle exporting a single value from a single field, such as the user's `name` in this query:

```graphql
query GetLoggedInUserName {
  me {
    name @export(as: "search")
  }
}

query GetPostsContainingString @depends(on: "GetLoggedInUserName") {
  posts(filter: { search: $search }) {
    id
    title
  }
}
```

#### 2. Exporting a list of values from a single field

Fields returning lists should also be exportable. For instance, in the query below, the exported value is the list of names from the logged-in user's friends (hence the type of the `$search` variable went from `String` to `[String]`):

```graphql
query GetLoggedInUserFriendNames {
  me {
    friends {
      name @export(as: "search")
    }
  }
}

query GetPostsContainingLoggedInUserFriendNames @depends(on: "GetLoggedInUserFriendNames") {
  posts(filter: { searchAny: $search }) {
    id
    title
  }
}
```

#### 3. Exporting a dictionary of values, containing several fields from the same object

We may also need to export several properties from a same object. Then, `@export` also allows to export these properties to the same variable, as a dictionary of values.

For instance, the query could export both the `name` and `surname` fields from the user, and have a `searchByAnyProperty` input that receives a dictionary. This is done by appending the `affectAdditionalFieldsUnderPos` directive argument (see the documentation for **Multi-Field Directives**) pointing to the extra field(s):

```graphql
query GetLoggedInUserNameAndSurname {
  me {
    name
    surname
      @export(
        as: "search"
        affectAdditionalFieldsUnderPos: [1]
      )
  }
}

query GetPostsContainingLoggedInUserNameAndSurname @depends(on: "GetLoggedInUserNameAndSurname") {
  posts(filter: { searchByAnyProperty: $search }) {
    id
    title
  }
}
```

#### 4. Exporting a list of a dictionary of values, with each dictionary containing several fields from the same object

Similar to upgrading from a single value to a list of values, we can upgrade from a single dictionary to a list of dictionaries.

For instance, we could export fields `name` and `surname` from the list of the logged-in user's friends:

```graphql
query GetLoggedInUserFriendNamesAndSurnames {
  me {
    friends {
      name
      surname
        @export(
          as: "search"
          affectAdditionalFieldsUnderPos: [1]
        )
    }
  }
}

query GetPostsContainingLoggedInUserFriendNamesAndSurnames @depends(on: "GetLoggedInUserFriendNamesAndSurnames") {
  posts(filter: { searchAnyByAnyProperty: $search }) {
    id
    title
  }
}
``` -->

## Exporting values when iterating an array or JSON object

`@export` respects the cardinality from any encompassing meta-directive.

In particular, whenever `@export` is nested below a meta-directive that iterates on array items or JSON object properties (i.e. `@underEachArrayItem` and `@underEachJSONObjectProperty`), then the exported value will be an array.

This query:

```graphql
{
  post(by: { id: 19 }) {
    coreContentAttributeBlocks: blockFlattenedDataItems(
      filterBy: { include: "core/heading" }
    )
      @underEachArrayItem
        @underJSONObjectProperty(
          by: { path: "attributes.content" },
        )
          @export(
            as: "contentAttributes",
          )
  }
}
```

...produces `$contentAttributes` with value:

```json
[
  "List Block",
  "Columns Block",
  "Columns inside Columns (nested inner blocks)",
  "Life is so rich",
  "Life is so dynamic"
]
```

In contrast, the same query that accesses a specific item in the array instead of iterating over all of them (by replacing `@underEachArrayItem` with `@underArrayItem(index: 0)`) will export a single value.

This query:

```graphql
{
  post(by: { id: 19 }) {
    coreContentAttributeBlocks: blockFlattenedDataItems(
      filterBy: { include: "core/heading" }
    )
      @underArrayItem(index: 0)
        @underJSONObjectProperty(
          by: { path: "attributes.content" },
        )
          @export(
            as: "contentAttributes",
          )
  }
}
```

...produces `$contentAttributes` with value:

```json
"List Block"
```

## Directive execution order

If there are other directives before `@export`, the exported value will reflect the modifications by those previous directives.

For instance, in this query, depending on `@export` taking place before or after `@strUpperCase`, the result will be different:

```graphql
query One {
  id
    # First export "root", only then will be converted to "ROOT"
    @export(as: "id")
    @strUpperCase

  again: id
    # First convert to "ROOT" and then export this value
    @strUpperCase
    @export(as: "again")
}

query Two @depends(on: "One") {
  mirrorID: _echo(value: $id)
  mirrorAgain: _echo(value: $again)
}
```

Producing:

```json
{
  "data": {
    "id": "ROOT",
    "again": "ROOT",
    "mirrorID": "root",
    "mirrorAgain": "ROOT"
  }
}
```

## Execution in Persisted Queries

When a GraphQL query contains multiple operations in a Persisted Query, we can invoke the corresponding endpoint passing URL param `?operationName=...` with the name of the operation to execute; otherwise, the last operation will be executed.

For instance, to execute operation `GetPostsContainingString` in a Persisted Query with endpoint `/graphql-query/posts-with-user-name/`, we must invoke:

```http
https://mysite.com/graphql-query/posts-with-user-name/?operationName=GetPostsContainingString
```

## Examples

Import content from an external API endpoint:

```graphql
query FetchDataFromExternalEndpoint
{
  _sendJSONObjectItemHTTPRequest(input: { url: "https://site.com/wp-json/wp/posts/1" } )
    @export(as: "externalData")
    @remove
}

query ManipulateDataIntoInput @depends(on: "FetchDataFromExternalEndpoint")
{
  title: _objectProperty(
    object: $externalData,
    by: {
      path: "title.rendered"
    }
  ) @export(as: "postTitle")

  excerpt: _objectProperty(
    object: $externalData,
    by: {
      key: "excerpt"
    }
  ) @export(as: "postExcerpt")
}

mutation CreatePost @depends(on: "ManipulateDataIntoInput")
{
  createPost(input: {
    title: $postTitle
    excerpt: $postExcerpt
  }) {
    id
  }
}
```

Retrieve the data for a post, transform it, and store it again:

```graphql
query GetPostData(
  $postId: ID!
) {
  post(by: {id: $postId}) {
    id
    rawTitle @export(as: "postTitle")
    rawContent @export(as: "postContent")
  }
}

query AdaptPostData(
  $replaceFrom: String!,
  $replaceTo: String!
)
  @depends(on: "GetPostData")
{
  adaptedPostTitle: _strReplace(
    search: $replaceFrom
    replaceWith: $replaceTo
    in: $postTitle
  )
    @export(as: "adaptedPostTitle")

  adaptedPostContent: _strReplace(
    search: $replaceFrom
    replaceWith: $replaceTo
    in: $postContent
  )
    @export(as: "adaptedPostContent")
}

mutation StoreAdaptedPostData(
  $postId: ID!
)
  @depends(on: "AdaptPostData")
{
  updatePost(input: {
    id: $postId,
    title: $adaptedPostTitle,
    contentAs: { html: $adaptedPostContent },
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
      rawTitle
      rawContent
    }
  }
}
```

Update a post if it exists, or show an error message otherwise:

```graphql
query GetPost($id: ID!) {
  post(by:{id: $id}) {
    id
    title
  }
  _notNull(value: $__post) @export(as: "postExists")
}

query FailIfPostNotExists($id: ID!)
  @skip(if: $postExists)
  @depends(on: "GetPost")
{
  errorMessage: _sprintf(
    string: "There is no post with ID '%s'",
    values: [$id]
  ) @remove
  _fail(
    message: $__errorMessage
    data: {
      id: $id
    }
  ) @remove
}

mutation UpdatePost($id: ID!, $postTitle: String)
  @include(if: $postExists)
  @depends(on: "GetPost")
{
  updatePost(input: {
    id: $id,
    title: $postTitle,
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

query MaybeUpdatePost
  @depends(on: [
      "FailIfPostNotExists",
      "UpdatePost"
  ])
{
  id @remove
}
```

Log the user in before executing a mutation, and out immediately after:

```graphql
mutation LogUserIn(
  $username: String!
  $password: String!
) {
  loginUser(by: {
    credentials: {
      usernameOrEmail: $username,
      password: $password
    }
  }) @remove {
    status
    user {
      id
      username
    }
  }
}

mutation AddComment(
  $customPostId: ID!
  $commentContent: HTML!
)
  @depends(on: "LogUserIn")
{
  addCommentToCustomPost(input: {
    customPostID: $customPostId,
    commentAs: { html: $commentContent }
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    comment {
      id
      parent {
        id
      }
      content
      date
      author {
        name
        email
      }
    }
  }
}

mutation LogUserOut
  @depends(on: "AddComment")
{
  logoutUser @remove {
    status
    userID
  }
}

query ExecuteAllAddCommentOperations
  @depends(on: "LogUserOut")
{
  id @remove
}
```

Conditionally log the user in before executing a mutation, if provided:

```graphql
query ExportUserLogin(
  $username: String
) {
  _notNull(value: $username)
    @export(as: "hasUsername")
    @remove
}

mutation MaybeLogUserIn(
  $username: String
  $password: String
)
  @depends(on: "ExportUserLogin")
  @include(if: $hasUsername)
{
  loginUser(by: {
    credentials: {
      usernameOrEmail: $username,
      password: $password
    }
  }) @remove {
    status
    user {
      id
      username
    }
  }
}

mutation AddComment(
  $customPostId: ID!
  $commentContent: HTML!
)
  @depends(on: "MaybeLogUserIn")
{
  addCommentToCustomPost(input: {
    customPostID: $customPostId,
    commentAs: { html: $commentContent }
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    comment {
      id
      parent {
        id
      }
      content
      date
      author {
        name
        email
      }
    }
  }
}

mutation MaybeLogUserOut
  @depends(on: "AddComment")
  @include(if: $hasUsername)
{
  logoutUser @remove {
    status
    userID
  }
}

query ExecuteAllAddCommentOperations
  @depends(on: "MaybeLogUserOut")
{
  id @remove
}
```
<!-- 
## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-feature-bundled-extensions/docs/modules/all-feature-bundled-extensions/en.md)
- [“Caching” Bundle](../../../../../bundle-extensions/caching/docs/modules/caching/en.md)
- [“Easy WordPress Bulk Transform & Update” Bundle](../../../../../bundle-extensions/easy-wordpress-bulk-transform-and-update/docs/modules/easy-wordpress-bulk-transform-and-update/en.md)
- [“Private GraphQL Server for WordPress” Bundle](../../../../../bundle-extensions/private-graphql-server-for-wordpress/docs/modules/private-graphql-server-for-wordpress/en.md)
- [“Responsible WordPress Public API” Bundle](../../../../../bundle-extensions/responsible-wordpress-public-api/docs/modules/responsible-wordpress-public-api/en.md)
- [“Selective Content Import, Export & Sync for WordPress” Bundle](../../../../../bundle-extensions/selective-content-import-export-and-sync-for-wordpress/docs/modules/selective-content-import-export-and-sync-for-wordpress/en.md)
- [“Simplest WordPress Content Translation” Bundle](../../../../../bundle-extensions/simplest-wordpress-content-translation/docs/modules/simplest-wordpress-content-translation/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md)
- [“Unhindered WordPress Email Notifications” Bundle](../../../../../bundle-extensions/unhindered-wordpress-email-notifications/docs/modules/unhindered-wordpress-email-notifications/en.md)
- [“Versatile WordPress Request API” Bundle](../../../../../bundle-extensions/versatile-wordpress-request-api/docs/modules/versatile-wordpress-request-api/en.md) -->
