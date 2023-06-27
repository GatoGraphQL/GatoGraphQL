# Response Error Trigger

Explicitly add an error entry to the response to trigger the failure of the GraphQL request (whenever a field does not meet the expected conditions)

## Description

This module adds global field `_fail` and directive `@fail` to the GraphQL schema, which add an entry to the `errors` property in the response.

Field `_fail` adds the error always, and directive `@fail` whenever the condition under argument `condition` is met (either if the field value is null, or empty; by default, it is `IS_NULL`):

```graphql
query {
  _fail(message: "Some error")
  
  posts {
    featuredImage @fail(
      # condition: IS_NULL, <= This is the default value
      message: "The post does not have a featured image"
    ) {
      id
      src
    }
  }
  
  users {
    name @fail(
      condition: IS_EMPTY,
      message: "The retrieved user does not have a name"
    )
  }
}
```

Both of them can also receive argument `data`, to provide contextual information in the error response.

These schema elements are useful to explicitly indicate that there is an error in the executed GraphQL query, whenever such an error does not happen on natural circumstances.

Then, in our application on the client side (such as JavaScript with a headless setup), we can check if entry `errors` exists and, based on that, either process the GraphQL response or show an error message to the user:

```js
/**
 * If the response contains error(s), return a concatenated error message
 *
 * @param {Object} response A response object from the GraphQL server
 * @return {string|null} The error message or nothing
 */
const maybeGetErrorMessage = (response) => {
  if (response.errors && response.errors.length) {
    return sprintf(
      __(`The API produced the following error(s): "%s"`, 'gato-graphql'),
      response.errors.map(error => error.message).join( __('", "') )
    );
  }
  return null;
}

const maybeErrorMessage = maybeGetErrorMessage(response);
if (maybeErrorMessage) {
  // Show error to the user
  // ...
} else {
  // Process response
  // ...
}
```

## Examples

Retrieving a post with a non-existent ID will naturally return `null`. If we need to treat this condition as an error, we can use directive `@fail`:

```graphql
query GetPost($id: ID!) {
  post(by:{id: $id})
    @fail(
      message: "There is no post with the provided ID"
      data: {
        id: $id
      }
    )
  {
    id
    title
  }
}
```

In combination with the **Multiple Query Execution** extension, we can obtain the same results using `_fail` (notice that operation `FailIfPostNotExists` is not executed whenever `$postExists` is `true`):

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
```

We can use `_fail` to ensure that the user with given email does not yet exist:

```graphql
query EnsureUserDoesNotExist($userEmail: Email!) {
  user( by: { email: $userEmail } ) {
    _fail(
      message: "User with given email already exists"
      data: {
        email: $userEmail
      }
    )
  }
}

mutation CreateUser($userData: JSONObject!)
  @depends(on: "EnsureUserDoesNotExist")
{
  # ...
}
```

We can also use `_fail` to check if retrieving data from an external API produced errors:

```graphql
query ConnectToExternalGraphQLAPI($endpoint: String!, $query: String!) {
  externalData: _sendGraphQLHTTPRequest(
    input: {
      endpoint: $endpoint
      query: $query
    }
  ) @export(as: "externalData")
  _propertyIsSetInJSONObject(
    object: $__externalData
    by: {
      key: "errors"
    }
  ) @export(as: "endpointHasErrors")
}

query FailIfExternalAPIHasErrors($endpoint: String!)
  @include(if: $endpointHasErrors)
  @depends(on: "ConnectToExternalGraphQLAPI")
{
  errorMessage: _sprintf(
    string: "Connecting to endpoint %s produced errors",
    values: [$endpoint]
  ) @remove
  data: _objectProperty(
    object: $externalData,
    by: {
      key: "errors"
    }
  ) @remove
  _fail(
    message: $__errorMessage
    data: {
      endpoint: $endpoint
      endpointData: $__data
    }
  ) @remove
}

query GetExternalAPIData
  @skip(if: $endpointHasErrors)
  @depends(on: "ConnectToExternalGraphQLAPI")
{
  data: _objectProperty(
    object: $externalData,
    by: {
      key: "data"
    }
  )
}
```
