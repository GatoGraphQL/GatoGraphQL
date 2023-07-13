# Retrieving data from an external API

The [**HTTP Client**](https://gatographql/extensions/http-client/) extension allows us to execute HTTP requests against a webserver.

This recipe demonstrates how to fetch data from an external API, by:

- Retrieving the members of an email list from Mailchimp's REST API, extracting their emails, and doing something with this data
- Retrieving repositories from GitHub's GraphQL API

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

## Connecting to GraphQL APIs

(As the Mailchimp API doesn't support GraphQL, we switch to a different API for this example.)

[**HTTP Client**](https://gatographql/extensions/http-client/) also provides a function field to conveniently connect to GraphQL APIs.

Field `_sendGraphQLHTTPRequest` accepts those inputs expected by GraphQL (the query, variables and operation name), executes the GraphQL query against the provided endpoint, and converts the response to `JSONObject`.

This query connects to [GitHub's GraphQL API](https://docs.github.com/en/graphql) and retrieves the list of repos for the provided owner:

```graphql
query FetchGitHubRepositories(
  $authorizationToken: String!
  $login: String!
  $numberRepos: Int! = 3
) {
  _sendGraphQLHTTPRequest(input:{
    endpoint: "https://api.github.com/graphql",
    query: """
    
query GetRepositoriesByOwner($login: String!, $numberRepos: Int!) {
  repositoryOwner(login: $login) {
    repositories(first: $numberRepos) {
      nodes {
        id
        name
        description
      }
    }
  }
}

    """,
    variables: [
      {
        name: "login",
        value: $login
      },
      {
        name: "numberRepos",
        value: $numberRepos
      }
    ],
    options: {
      auth: {
        password: $authorizationToken
      }
    }
  })
}
```

Passing these `variables`:

```json
{
  "authorizationToken": "{ GITHUB ACCESS TOKEN }",
  "login": "leoloso"
}
```

...produces this response:

```json
{
  "data": {
    "_sendGraphQLHTTPRequest": {
      "data": {
        "repositoryOwner": {
          "repositories": {
            "nodes": [
              {
                "id": "MDEwOlJlcG9zaXRvcnk2NjcyMTIyNw==",
                "name": "PoP",
                "description": "Monorepo of the PoP project, including: a server-side component model in PHP, a GraphQL server, a GraphQL API plugin for WordPress, and a website builder"
              },
              {
                "id": "MDEwOlJlcG9zaXRvcnkxODQ1MzE5NzA=",
                "name": "PoP-API-WP",
                "description": "Bootstrap a PoP API for WordPress"
              },
              {
                "id": "MDEwOlJlcG9zaXRvcnkxOTYwOTk0MzQ=",
                "name": "leoloso.com",
                "description": "My personal site, based on Hylia (https://hylia.website)"
              }
            ]
          }
        }
      }
    }
  }
}
```

## Fetching data from multiple APIs

We can send multiple HTTP requests to different URLs, whether asynchronously (i.e. in parallel) or synchronously (one after the other), fetching data from them all at the same time.

Every one of the HTTP request fields explored above has a corresponding "multiple" field:

- `_sendHTTPRequests`
- `_sendJSONObjectItemHTTPRequests`
- `_sendJSONObjectCollectionHTTPRequests`
- `_sendGraphQLHTTPRequests`

This GraphQL query retrieves weather forecast data for multiple regions:

```graphql
{
  _sendJSONObjectItemHTTPRequests(inputs: [
    {
      url: "https://api.weather.gov/gridpoints/TOP/31,80/forecast"
    },
    {
      url: "https://api.weather.gov/gridpoints/TOP/41,55/forecast"
    }
  ])
}
```

...producing:

```json
{
  "data": {
    "_sendJSONObjectItemHTTPRequests": [
      {
        "type": "Feature",
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -97.1089731,
                39.766826299999998
              ],
              [
                -97.108526900000001,
                39.744778799999999
              ]
            ]
          ]
        },
        "properties": {
          "updated": "2022-03-04T09:39:46+00:00",
          "units": "us",
          "forecastGenerator": "BaselineForecastGenerator",
          "generatedAt": "2022-03-04T10:31:47+00:00",
          "updateTime": "2022-03-04T09:39:46+00:00",
          "validTimes": "2022-03-04T03:00:00+00:00/P7DT22H",
          "elevation": {
            "unitCode": "wmoUnit:m",
            "value": 441.95999999999998
          }
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -96.812529900000001,
                39.218048000000003
              ],
              [
                -96.812148500000006,
                39.195940300000004
              ]
            ]
          ]
        },
        "properties": {
          "updated": "2022-03-04T09:39:46+00:00",
          "units": "us",
          "forecastGenerator": "BaselineForecastGenerator",
          "generatedAt": "2022-03-04T10:42:26+00:00",
          "updateTime": "2022-03-04T09:39:46+00:00",
          "validTimes": "2022-03-04T03:00:00+00:00/P7DT22H",
          "elevation": {
            "unitCode": "wmoUnit:m",
            "value": 409.04160000000002
          }
        }
      }
    ]
  }
}
```

## Extracting data from the API response

Back to Mailchimp's API, let's extract the list of all the email addresses from the response. These are contained under the `email_address` field under `members`:

```json
{
  "data": {
    "_sendJSONObjectItemHTTPRequest": {
      "members": [
        {
          "email_address": "vinesh@yahoo.com",
          // ...
        },
        {
          "email_address": "thiago@hotmail.com",
          // ...
        },
        // ...
      ]
    }
  }
}
```

The [**Field Value Iteration and Manipulation**](https://gatographql.com/extensions/field-value-iteration-and-manipulation/) extension provides [composable directives](https://gatographql.com/guides/schema/using-composable-directives/) that iterate over the inner elements of arrays or objects, and apply their nested directive(s) under those elements:

- `@underArrayItem`: Operate on a specific item from the array
- `@underJSONObjectProperty`: Operate on a specific entry from the JSON object
- `@underEachArrayItem`: Operate under all items from the array
- `@underEachJSONObjectProperty`: Operate under all entries from the JSON object

This GraphQL query navigates to each of the `email_address` fields, and exports their value to dynamic variable `$mailchimpListMemberEmails`:

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
```

We can visualize the entries by printing the value of the dynamic variable:

```graphql
query PrintMailchimpSubscriberEmails
  @depends(on: "GetDataFromMailchimp")
{
  mailchimpListMemberEmails: _echo(value: $mailchimpListMemberEmails)
}
```

...producing:

```json
{
  "data": {
    "mailchimpListMembersJSONObject": {
      // ...
    },
    "mailchimpListMemberEmails": [
      "vinesh@yahoo.com",
      "thiago@hotmail.com",
      // ...
    ]
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Notice that, even though dynamic variable `$mailchimpListMemberEmails` is a list, `@export` does not have argument `type: LIST`.

This is because whenever `@export` is nested below `@underEachArrayItem` (or `@underEachJSONObjectProperty`), the [exported value will already be an array](https://gatographql.com/guides/schema/executing-multiple-queries-concurrently/#heading-exporting-values-when-iterating-an-array-or-json-object).

</div>

## Combining data from Mailchimp subscribers and website users

In the website we can store information from the Mailchimp subscribers, using their email address as the common ID between the two applications (Mailchimp and our website).

After placing the subscriber emails under dynamic variable `$mailchimpListMemberEmails`, we can fetch those users from our site:

```graphql
query GetUsersUsingMailchimpSubscriberEmails
  @depends(on: "GetDataFromMailchimp")
{
  users(filter: { searchBy: { emails: $mailchimpListMemberEmails} } ) {
    id
    name
    email
  }
}
```

The response will be:

```json
{
  "data": {
    "mailchimpListMembersJSONObject": {
      // ...
    },
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

Having retrieved the users, we can apply any desired operation on them (execute a mutation to update their data, send an email, etc).
