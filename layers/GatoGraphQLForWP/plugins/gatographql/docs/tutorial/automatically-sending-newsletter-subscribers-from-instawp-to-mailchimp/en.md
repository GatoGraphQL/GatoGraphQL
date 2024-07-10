# Lesson 31: Automatically sending newsletter subscribers from InstaWP to Mailchimp

This GraphQL query captures the email from the visitors who ticked the "Subscribe to mailing list" checkbox from InstaWP (when creating a new sandbox site), and subscribes this email to a Mailchimp list:

```graphql
query HasSubscribedToNewsletter {
  hasSubscriberOptIn: _httpRequestHasParam(name: "marketing_optin")
  subscriberOptIn: _httpRequestStringParam(name: "marketing_optin")
  isNotSubscriberOptInNAValue: _notEquals(value1: $__subscriberOptIn, value2: "NA")
  subscribedToNewsletter: _and(values: [$__hasSubscriberOptIn, $__isNotSubscriberOptInNAValue])
    @export(as: "subscribedToNewsletter")
}

query MaybeSubscribeEmailOnMailchimpList(
  $mailchimpDataCenterCode: String!
  $mailchimpAudienceID: String!
)
   @depends(on: "HasSubscribedToNewsletter")
   @include(if: $subscribedToNewsletter)
{
  subscriberEmail: _httpRequestStringParam(name: "email")
  
  mailchimpUsername: _env(name: "MAILCHIMP_API_CREDENTIALS_USERNAME")
    @remove
  mailchimpPassword: _env(name: "MAILCHIMP_API_CREDENTIALS_PASSWORD")
    @remove
  
  mailchimpAPIEndpoint: _sprintf(
    string: "https://%s.api.mailchimp.com/3.0/lists/%s/members",
    values: [$mailchimpDataCenterCode, $mailchimpAudienceID]
  )
  
  mailchimpListMembersJSONObject: _sendJSONObjectItemHTTPRequest(input: {
    url: $__mailchimpAPIEndpoint,
    method: POST,
    options: {
      auth: {
        username: $__mailchimpUsername,
        password: $__mailchimpPassword
      },
      json: {
        email_address: $__subscriberEmail,
        status: "subscribed"
      }
    }
  })
}
```

And define in `wp-config.php`:

```php
define( 'MAILCHIMP_API_CREDENTIALS_USERNAME', '{ username }' );
define( 'MAILCHIMP_API_CREDENTIALS_PASSWORD', '{ password }' );
```

## Step by step analysis of the GraphQL query

Read blog posts:

- [🚀 Automatically sending the newsletter subscribers from InstaWP to Mailchimp](https://gatographql.com/blog/instawp-gatographql/)
- [👨🏻‍🏫 GraphQL query to automatically send the newsletter subscribers from InstaWP to Mailchimp](https://gatographql.com/blog/instawp-gatographql-query/)
