# Lesson 18: Interacting with external services via webhooks

A webhook is an HTTP-based callback function that an external service calls to notify of some event, passing a payload of data along with it. Webhooks enable different webapps and services to communicate with each other.

Some examples of services invoking webhooks include:

- GitHub, when a repository has a commit pushed
- Dropbox, when a document is updated
- WooCommerce, when an order is added
- Microsoft Teams, to receive rich text messages and post in public channels
- ConvertKit, when a subscriber is activated

With Gato GraphQL, we can create Persisted Queries that act as webhooks:

- Because the Persisted Query is exposed under its own URL, it can be used as the target for the webhook
- Every Persisted Query can deal exclusively with some specific webhook

In this tutorial lesson, we will create a Persisted Query to interact with ConvertKit.

## Browsing the webhook documentation

The first step is to read the documentation for the website, and understand what data is sent via the payload.

Analysing [webhooks in ConvertKit](https://developers.convertkit.com/#webhooks), subscriber-related events send a `POST` request to our URL with a JSON payload like this:

```json
{
  "subscriber": {
    "id": 1,
    "first_name": "John",
    "email_address": "John@example.com",
    "state": "active",
    "created_at": "2018-02-15T19:40:24.913Z",
    "fields": {
      "My Custom Field": "Value"
    }
  }
}
```

## Extracting the data from the payload

Because the request is sent via `POST`, we must extract the data from the body of the HTTP request (which is supported via the [**HTTP Request via Schema**](https://gatographql.com/extensions/http-request-via-schema/) extension).

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

If the HTTP request is executed via `GET`, then the Persisted Query can directly [obtain the data items from the URL parameters](https://gatographql.com/guides/use/creating-a-persisted-query/#heading-making-the-persisted-query-dynamic-via-url-params).

</div>

This GraphQL query retrieves the body of the request and converts it to a JSON object. Then it extracts items `"subscriber.first_name"` and `"subscriber.email_address"` from the JSON object, and exports them as dynamic variables:

```graphql
query ExtractPayloadData {
  body: _httpRequestBody
  bodyJSONObject: _strDecodeJSONObject(string: $__body)

  subscriberFirstName: _objectProperty(
    object: $__bodyJSONObject,
    by: { path: "subscriber.first_name" }
  )
    @export(as: "subscriberFirstName")
  
  subscriberEmail: _objectProperty(
    object: $__bodyJSONObject,
    by: { path: "subscriber.email_address" }
  )
    @export(as: "subscriberEmail")
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

The [**HTTP Request via Schema**](https://gatographql.com/extensions/http-request-via-schema/) extension allows us to retrieve all of the current HTTP request data, via the following fields:

- `_httpRequestBody`: Body of the HTTP request
- `_httpRequestClientHost`: Client host
- `_httpRequestClientIP`: Client IP address (or `null` if the server is not properly configured)
- `_httpRequestCookie`: Request cookie value
- `_httpRequestCookies`: Request cookies
- `_httpRequestDomain`: Domain of the requested URL
- `_httpRequestFullURL`: Requested URL (including the query params)
- `_httpRequestHasCookie`: Does the request contain a certain cookie?
- `_httpRequestHasHeader`: Does the request contain a certain header?
- `_httpRequestHasParam`: Does the request contain a certain param?
- `_httpRequestHeader`: Request header value
- `_httpRequestHeaders`: Request headers
- `_httpRequestHost`: Host of the requested URL
- `_httpRequestMethod`: Request method
- `_httpRequestStringParam`: Value of a param (passed via POST or GET) of type `?param=value`
- `_httpRequestStringListParam`: Value of a param (passed via POST or GET) of type `?param[]=value1&param[]=value2`
- `_httpRequestParams`: Params passed whether via POST or via the URL query
- `_httpRequestProtocol`: Request protocol
- `_httpRequestQuery`: Query params string
- `_httpRequestReferer`: Request referer
- `_httpRequestRequestTime`: Timestamp of the start of the request
- `_httpRequestScheme`: Scheme of the requested URL
- `_httpRequestServerIP`: Server IP address
- `_httpRequestURL`: Requested URL (without query params)
- `_httpRequestURLPath`: Asolute path (starting with "/") of the requested URL
- `_httpRequestUserAgent`: User agent

</div>

## Executing some action with the data

Once we have extracted the data from the payload, we can execute some action with it.

This GraphQL query deals with the `subscriber.subscriber_unsubscribe` event, to send an email to the person who unsubscribed, asking for feedback.

```graphql
query CreateEmailMessage
  @depends(on: "ExtractPayloadData")
{
  emailMessageTemplate: _strConvertMarkdownToHTML(
    text: """

Hey {$subscriberFirstName}, it's sad to let you go!

Please be welcome to complete [this form](https://forms.gle/FpXNromWAsZYC1zB8) and let us know if there is anything we can do better.

Thanks. Hope to see you back!

    """
  )
  emailMessage: _strReplaceMultiple(
    search: ["{$subscriberFirstName}"],
    replaceWith: [$subscriberFirstName],
    in: $__emailMessageTemplate
  )
    @export(as: "emailMessage")
}

mutation SendEmail @depends(on: "CreateEmailMessage") {
  _sendEmail(
    input: {
      to: $subscriberEmail
      subject: "Would you like to give us feedback on how we can improve?"
      messageAs: {
        html: $emailMessage
      }
    }
  ) {
    status
  }
}
```
