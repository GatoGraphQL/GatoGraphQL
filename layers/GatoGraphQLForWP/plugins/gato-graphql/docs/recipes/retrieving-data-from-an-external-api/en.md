# Retrieving data from an external API

Idea: Connect to Mailchimp, extract the email of those users, and fetch extra data from them.

More idea: Next recipe is the one combining data from different sources!


Mailchimp API: https://mailchimp.com/developer/marketing/docs/fundamentals/

Query to retrieve List members:

```graphql
{
  mailchimpListMembersJSONObject: _sendJSONObjectItemHTTPRequest(input: {
    url: "https://us7.api.mailchimp.com/3.0/lists/{LIST_ID}/members",
    method: GET,
    options: {
      auth: {
        username: "{USER}",
        password: "{API_TOKEN}"
      }
    }
  })
}
```

Response

```json
{
  "data": {
    "mailchimpListMembersJSONObject": {
      "members": [
        {
          "id": "mSjGOg5qSb3dKTxPU9lhRZCxHGug8Mrt",
          "email_address": "some@email.com",
          "unique_email_id": "KObAXbEO3X",
          "contact_id": "JiCdz5EY67m3PKugW3bRE9VI1WjiBbjq",
          "full_name": "Vinesh Munak",
          "web_id": 443344389,
          "email_type": "html",
          "status": "subscribed",
          "consents_to_one_to_one_messaging": true,
          "merge_fields": {
            "FNAME": "Vinesh",
            "LNAME": "Munak",
            "ADDRESS": {
              "addr1": "",
              "addr2": "",
              "city": "",
              "state": "",
              "zip": "",
              "country": "IN"
            },
            "PHONE": "",
            "BIRTHDAY": ""
          },
          "stats": {
            "avg_open_rate": 0.8,
            "avg_click_rate": 0.6
          },
          "ip_signup": "",
          "timestamp_signup": "",
          "ip_opt": "218.115.112.129",
          "timestamp_opt": "2020-12-31T06:55:17+00:00",
          "member_rating": 4,
          "last_changed": "2020-12-31T06:55:17+00:00",
          "language": "",
          "vip": false,
          "email_client": "",
          "location": {
            "latitude": 2.18,
            "longitude": 99.47,
            "gmtoff": 8,
            "dstoff": 8,
            "country_code": "MY",
            "timezone": "asia/kuala_lumpur",
            "region": "10"
          },
          "source": "Admin Add",
          "tags_count": 0,
          "tags": [],
          "list_id": "9nrwpfj0ou"
        },
        {
          // ...
        }
      ],
      "list_id": "9nrwpfj0ou",
      "total_items": 4927,
      "_links": [
        {
          // ...
        },
        // ...
      ]
    }
  }
}
```

Add tips on all methods.

Mention that `_sendJSONObjectItemHTTPRequest` is a shortcut, could've done this too (and can this way retrieve additional data):

```graphql
{
  mailchimpListMembersHTTPResponse: _sendHTTPRequest(input: {
    url: "https://us7.api.mailchimp.com/3.0/lists/{LIST_ID}/members",
    method: GET,
    options: {
      auth: {
        username: "{USER}",
        password: "{API_TOKEN}"
      }
    }
  }) {
    contentType
    statusCode
    headers

    body @remove
    bodyJSONObject: _strDecodeJSONObject(string: $__body)
  }
}
```

Response

```json
{
  "data": {
    "mailchimpListMembersHTTPResponse": {
      "contentType": "application/json; charset=utf-8",
      "statusCode": 200,
      "headers": {
        "Server": "openresty",
        "Content-Type": "application/json; charset=utf-8",
        "Vary": "Accept-Encoding",
        "X-Request-Id": "79104d0f-41bd-c438-fdc2-87p56s3cx7cd",
        "Link": "<https://us7.api.mailchimp.com/schema/3.0/Lists/Members/Collection.json>; rel=\"describedBy\"",
        "Content-Length": "34129",
        "Date": "Wed, 12 Jul 2023 11:47:29 GMT",
        "Connection": "keep-alive"
      },
      "bodyJSONObject": {
        "members": [
          {
            "id": "mSjGOg5qSb3dKTxPU9lhRZCxHGug8Mrt",
            "email_address": "some@email.com",
            "unique_email_id": "KObAXbEO3X",
            "contact_id": "JiCdz5EY67m3PKugW3bRE9VI1WjiBbjq",
            "full_name": "Vinesh Munak",
            "web_id": 443344389,
            "email_type": "html",
            "status": "subscribed",
            "consents_to_one_to_one_messaging": true,
            "merge_fields": {
              "FNAME": "Vinesh",
              "LNAME": "Munak",
              "ADDRESS": {
                "addr1": "",
                "addr2": "",
                "city": "",
                "state": "",
                "zip": "",
                "country": "IN"
              },
              "PHONE": "",
              "BIRTHDAY": ""
            },
            "stats": {
              "avg_open_rate": 0.8,
              "avg_click_rate": 0.6
            },
            "ip_signup": "",
            "timestamp_signup": "",
            "ip_opt": "218.115.112.129",
            "timestamp_opt": "2020-12-31T06:55:17+00:00",
            "member_rating": 4,
            "last_changed": "2020-12-31T06:55:17+00:00",
            "language": "",
            "vip": false,
            "email_client": "",
            "location": {
              "latitude": 2.18,
              "longitude": 99.47,
              "gmtoff": 8,
              "dstoff": 8,
              "country_code": "MY",
              "timezone": "asia/kuala_lumpur",
              "region": "10"
            },
            "source": "Admin Add",
            "tags_count": 0,
            "tags": [],
            "list_id": "9nrwpfj0ou"
          },
          {
            // ...
          }
        ],
        "list_id": "9nrwpfj0ou",
        "total_items": 4927,
        "_links": [
          {
            // ...
          },
          // ...
        ]
      }
    }
  }
}
```

## Extracting list members

Use:

```graphql
query GetDataFromMailchimp {
  mailchimpListMembersJSONObject: _sendJSONObjectItemHTTPRequest(input: {
    url: "https://us7.api.mailchimp.com/3.0/lists/{LIST_ID}/members",
    method: GET,
    options: {
      auth: {
        username: "{USER}",
        password: "{API_TOKEN}"
      }
    }
  })
    @underJSONObjectProperty(by: { key: "members"})
      @underEachArrayItem
        @underJSONObjectProperty(by: { key: "email_address"})
          @export(as: "mailchimpListMemberEmails")
}

query CombineData
  @depends(on: "GetDataFromMailchimp")
{
  mailchimpListMemberEmails: _echo(value: $mailchimpListMemberEmails)
  users(filter: { searchBy: { emails: $mailchimpListMemberEmails} } ) {
    id
    name
    email
  }
}
```

Tips:

It is `@export(as: "mailchimpListMemberEmails")` and not `@export(as: "mailchimpListMemberEmails", type: LIST)` because `@underEachArrayItem` respects cardinality

---

Tips: talk about:

underJSONObjectProperty
underEachArrayItem

## GraphQL query to retrieve users subscribed to Mailchimp account

Get the email from the API, get `users` with those emails, print their data.

