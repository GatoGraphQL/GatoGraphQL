# Retrieving data from an external API

The [**HTTP Client**](https://gatographql/extensions/http-client/) extension allows us to execute HTTP requests against a webserver.

This recipe demonstrates how to fetch data from an external API. We will connect to Mailchimp's API to retrieve an email list's subscribers, extract their email (and other data), and do something with this data.

## Executing an HTTP request

The [docs for Mailchimp's API](https://mailchimp.com/developer/marketing/docs/fundamentals/#connecting-to-the-api) explain that we can connect to it by sending a `GET` request.

Fetching data from the email list's subscribers is done like this:

```bash
curl --request GET \
  --url 'https://us7.api.mailchimp.com/3.0/lists/{LIST_ID}/members' \
  --user 'username:password'
```

Let's replicate this in Gato GraphQL.

We execute an HTTP request via global field `_sendHTTPRequest` (provided via the [**HTTP Client**](https://gatographql/extensions/http-client/) extension):

```graphql
query {
  _sendHTTPRequest(input: {
    url: "https://us7.api.mailchimp.com/3.0/lists/{LIST_ID}/members",
    method: GET,
    options: {
      auth: {
        username: "{USER}",
        password: "{API_TOKEN}"
      }
    }
  }) {
    body
    contentType
    statusCode
    headers
    serverHeader: header(name: "Server")
  }
}
```

Field `_sendHTTPRequest` returns an object of type `HTTPResponse`. After executing the query, notice that the `body` field (of type `String`) contains the raw content of the response:

```json
{
  "data": {
    "_sendHTTPRequest": {
      "body": "{\"members\":[{\"id\":\"mSjGOg5qSb3dKTxPU9lhRZCxHGug8Mrt\",\"email_address\":\"vinesh@yahoo.com\",\"unique_email_id\":\"KObAXbEO3X\",\"contact_id\":\"JiCdz5EY67m3PKugW3bRE9VI1WjiBbjq\",\"full_name\":\"Vinesh Munak\",\"web_id\":443344389,\"email_type\":\"html\",\"status\":\"subscribed\",\"consents_to_one_to_one_messaging\":true,\"merge_fields\":{\"FNAME\":\"Vinesh\",\"LNAME\":\"Munak\",\"ADDRESS\":{\"addr1\":\"\",\"addr2\":\"\",\"city\":\"\",\"state\":\"\",\"zip\":\"\",\"country\":\"IN\"},\"PHONE\":\"\",\"BIRTHDAY\":\"\"},\"stats\":{\"avg_open_rate\":0.8,\"avg_click_rate\":0.6},\"ip_signup\":\"\",\"timestamp_signup\":\"\",\"ip_opt\":\"218.115.112.129\",\"timestamp_opt\":\"2020-12-31T06:55:17+00:00\",\"member_rating\":4,\"last_changed\":\"2020-12-31T06:55:17+00:00\",\"language\":\"\",\"vip\":false,\"email_client\":\"\",\"location\":{\"latitude\":2.18,\"longitude\":99.47,\"gmtoff\":8,\"dstoff\":8,\"country_code\":\"MY\",\"timezone\":\"asia/kuala_lumpur\",\"region\":\"10\"},\"source\":\"Admin Add\",\"tags_count\":0,\"tags\":[],\"list_id\":\"9nrwpfj0ou\",\"_links\":[{...}]},{...}],\"total_items\":4927,\"_links\":[{...}]}",
      "contentType": "application/json; charset=utf-8",
      "statusCode": 200,
      "headers": {
        "Server": "openresty",
        "Content-Type": "application/json; charset=utf-8",
        "Vary": "Accept-Encoding",
        "X-Request-Id": "177551d0-82e9-3d61-a664-177f61b91f80",
        "Link": "<https://us7.api.mailchimp.com/schema/3.0/Lists/Members/Collection.json>; rel=\"describedBy\"",
        "Date": "Thu, 13 Jul 2023 04:57:42 GMT",
        "Transfer-Encoding": "chunked",
        "Connection": "keep-alive,Transfer-Encoding"
      },
      "serverHeader": "openresty"
    }
  }
}
```

As the content-type of the response is `application/json`, we can trasform the raw body content from `String` to `JSONObject` via field `_strDecodeJSONObject` (from the [**PHP Functions Via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension):

```graphql
query {
  _sendHTTPRequest(input: {
    url: "https://us7.api.mailchimp.com/3.0/lists/{LIST_ID}/members",
    method: GET,
    options: {
      auth: {
        username: "{USER}",
        password: "{API_TOKEN}"
      }
    }
  }) {
    body @remove
    bodyJSONObject: _strDecodeJSONObject(string: $__body)
  }
}
```

The body is now accessible as a JSON object:

```json
{
  "data": {
    "_sendHTTPRequest": {
      "bodyJSONObject": {
        "members": [
          {
            "id": "mSjGOg5qSb3dKTxPU9lhRZCxHGug8Mrt",
            "email_address": "vinesh@yahoo.com",
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
            "list_id": "9nrwpfj0ou",
            "_links": [
              {
                // ...
              },
              // ...
            ]
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

## Connecting to REST APIs

[**HTTP Client**](https://gatographql/extensions/http-client/) also provides function fields that already handle responses of content-type `application/json`, making these suitable for connecting to REST APIs:

- `_sendJSONObjectItemHTTPRequest`: It retrieves the response for a single JSON object
- `_sendJSONObjectCollectionHTTPRequest`: It retrieves the response for a collection of JSON objects

These fields already convert the response to `JSONObject` or `[JSONObject]`.

Adapting the previous query:

```graphql
query {
  _sendJSONObjectItemHTTPRequest(input: {
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

...produces this response:

```json
{
  "data": {
    "_sendJSONObjectItemHTTPRequest": {
      "members": [
        {
          "id": "mSjGOg5qSb3dKTxPU9lhRZCxHGug8Mrt",
          "email_address": "vinesh@yahoo.com",
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
          "list_id": "9nrwpfj0ou",
          "_links": [
            {
              // ...
            },
            // ...
          ]
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

Response:

```json

{
  "data": {
    "mailchimpListMemberEmails": [
      "vinesh@yahoo.com",
      "thiago@hotmail.com",
      // ...
    ],
    "users": [
      {
        "id": 88,
        "name": "Vinesh Munak",
        "email": "vinesh@yahoo.com"
      },
      {
        "id": 705,
        "name": "Thiago Barbossa",
        "email": "thiago@hotmail.com"
      }
    ]
  }
}
```
