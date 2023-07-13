# Retrieving data from an external API

The [**HTTP Client**](https://gatographql/extensions/http-client/) extension allows us to execute HTTP requests against a webserver.

This recipe demonstrates how to fetch data from an external API, by:

- Retrieving the members of an email list from Mailchimp's REST API, extracting their emails, and doing something with this data
- Retrieving repositories from GitHub's GraphQL API

## Executing an HTTP request

The [docs for Mailchimp's API](https://mailchimp.com/developer/marketing/docs/fundamentals/#connecting-to-the-api) explain that we must send a `GET` request against the REST API, to fetch an email list members' data:

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

## Connecting to a REST API

[**HTTP Client**](https://gatographql/extensions/http-client/) also provides function fields that already handle responses of content-type `application/json`, making these suitable for connecting to REST APIs:

- `_sendJSONObjectItemHTTPRequest`: When the content pertains a single JSON object
- `_sendJSONObjectCollectionHTTPRequest`: When the content pertains a collection of JSON objects

<!-- These fields already convert the response to `JSONObject` or `[JSONObject]`. -->

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

## Connecting to a GraphQL API

<!-- (As the Mailchimp API doesn't support GraphQL, we switch to a different API for this example.) -->

[**HTTP Client**](https://gatographql/extensions/http-client/) also provides a function field to conveniently connect to GraphQL APIs.

Field `_sendGraphQLHTTPRequest` accepts those inputs expected by GraphQL (the query, variables and operation name), executes the GraphQL query against the provided endpoint, and converts the response to `JSONObject`.

This query connects to [GitHub's GraphQL API](https://docs.github.com/en/graphql) and retrieves the list of repos for the indicated owner:

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

## Fetching data from multiple URLs

We can send HTTP requests to multiple URLs, fetching data from all of them at the same time.

Every one of the HTTP request fields explored above has a corresponding "multiple" field:

- `_sendHTTPRequests`
- `_sendJSONObjectItemHTTPRequests`
- `_sendJSONObjectCollectionHTTPRequests`
- `_sendGraphQLHTTPRequests`

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

All these fields have argument `async`, to indicate if to execute the multiple HTTP requests asynchronously or synchronously:

- Asynchronously: The HTTP requests are executed all together, in parallel
- Synchronously: Each HTTP request is sent out only after the previous one is completed

</div>

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
        "@context": [
          "https://geojson.org/geojson-ld/geojson-context.jsonld",
          {
            "@version": "1.1",
            "wx": "https://api.weather.gov/ontology#",
            "geo": "http://www.opengis.net/ont/geosparql#",
            "unit": "http://codes.wmo.int/common/unit/",
            "@vocab": "https://api.weather.gov/ontology#"
          }
        ],
        "type": "Feature",
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -97.137207,
                39.7444372
              ],
              [
                -97.1367549,
                39.7223799
              ],
              [
                -97.1080809,
                39.7227252
              ],
              [
                -97.10852700000001,
                39.7447825
              ],
              [
                -97.137207,
                39.7444372
              ]
            ]
          ]
        },
        "properties": {
          "updated": "2023-07-13T05:39:07+00:00",
          "units": "us",
          "forecastGenerator": "BaselineForecastGenerator",
          "generatedAt": "2023-07-13T06:44:24+00:00",
          "updateTime": "2023-07-13T05:39:07+00:00",
          "validTimes": "2023-07-12T23:00:00+00:00/P7DT2H",
          "elevation": {
            "unitCode": "wmoUnit:m",
            "value": 456.8952
          },
          "periods": [
            {
              "number": 1,
              "name": "Overnight",
              "startTime": "2023-07-13T01:00:00-05:00",
              "endTime": "2023-07-13T06:00:00-05:00",
              "isDaytime": false,
              "temperature": 68,
              "temperatureUnit": "F",
              "temperatureTrend": null,
              "probabilityOfPrecipitation": {
                "unitCode": "wmoUnit:percent",
                "value": null
              },
              "dewpoint": {
                "unitCode": "wmoUnit:degC",
                "value": 21.666666666666668
              },
              "relativeHumidity": {
                "unitCode": "wmoUnit:percent",
                "value": 100
              },
              "windSpeed": "5 mph",
              "windDirection": "NE",
              "icon": "https://api.weather.gov/icons/land/night/few?size=medium",
              "shortForecast": "Mostly Clear",
              "detailedForecast": "Mostly clear, with a low around 68. Northeast wind around 5 mph."
            },
            {
              "number": 2,
              "name": "Thursday",
              "startTime": "2023-07-13T06:00:00-05:00",
              "endTime": "2023-07-13T18:00:00-05:00",
              "isDaytime": true,
              "temperature": 90,
              "temperatureUnit": "F",
              "temperatureTrend": null,
              "probabilityOfPrecipitation": {
                "unitCode": "wmoUnit:percent",
                "value": null
              },
              "dewpoint": {
                "unitCode": "wmoUnit:degC",
                "value": 21.11111111111111
              },
              "relativeHumidity": {
                "unitCode": "wmoUnit:percent",
                "value": 100
              },
              "windSpeed": "5 to 10 mph",
              "windDirection": "NE",
              "icon": "https://api.weather.gov/icons/land/day/sct?size=medium",
              "shortForecast": "Mostly Sunny",
              "detailedForecast": "Mostly sunny, with a high near 90. Northeast wind 5 to 10 mph."
            },
            // ...
          ]
        }
      },
      {
        "@context": [
          "https://geojson.org/geojson-ld/geojson-context.jsonld",
          {
            "@version": "1.1",
            "wx": "https://api.weather.gov/ontology#",
            "geo": "http://www.opengis.net/ont/geosparql#",
            "unit": "http://codes.wmo.int/common/unit/",
            "@vocab": "https://api.weather.gov/ontology#"
          }
        ],
        "type": "Feature",
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -96.8406778,
                39.1956467
              ],
              [
                -96.8402904,
                39.1735282
              ],
              [
                -96.811767,
                39.1738261
              ],
              [
                -96.8121485,
                39.1959446
              ],
              [
                -96.8406778,
                39.1956467
              ]
            ]
          ]
        },
        "properties": {
          "updated": "2023-07-13T05:39:07+00:00",
          "units": "us",
          "forecastGenerator": "BaselineForecastGenerator",
          "generatedAt": "2023-07-13T07:07:02+00:00",
          "updateTime": "2023-07-13T05:39:07+00:00",
          "validTimes": "2023-07-12T23:00:00+00:00/P7DT2H",
          "elevation": {
            "unitCode": "wmoUnit:m",
            "value": 403.86
          },
          "periods": [
            {
              "number": 1,
              "name": "Overnight",
              "startTime": "2023-07-13T02:00:00-05:00",
              "endTime": "2023-07-13T06:00:00-05:00",
              "isDaytime": false,
              "temperature": 69,
              "temperatureUnit": "F",
              "temperatureTrend": null,
              "probabilityOfPrecipitation": {
                "unitCode": "wmoUnit:percent",
                "value": null
              },
              "dewpoint": {
                "unitCode": "wmoUnit:degC",
                "value": 22.22222222222222
              },
              "relativeHumidity": {
                "unitCode": "wmoUnit:percent",
                "value": 97
              },
              "windSpeed": "5 to 10 mph",
              "windDirection": "NE",
              "icon": "https://api.weather.gov/icons/land/night/few?size=medium",
              "shortForecast": "Mostly Clear",
              "detailedForecast": "Mostly clear, with a low around 69. Northeast wind 5 to 10 mph."
            },
            {
              "number": 2,
              "name": "Thursday",
              "startTime": "2023-07-13T06:00:00-05:00",
              "endTime": "2023-07-13T18:00:00-05:00",
              "isDaytime": true,
              "temperature": 93,
              "temperatureUnit": "F",
              "temperatureTrend": null,
              "probabilityOfPrecipitation": {
                "unitCode": "wmoUnit:percent",
                "value": null
              },
              "dewpoint": {
                "unitCode": "wmoUnit:degC",
                "value": 22.22222222222222
              },
              "relativeHumidity": {
                "unitCode": "wmoUnit:percent",
                "value": 100
              },
              "windSpeed": "5 to 10 mph",
              "windDirection": "NE",
              "icon": "https://api.weather.gov/icons/land/day/sct?size=medium",
              "shortForecast": "Mostly Sunny",
              "detailedForecast": "Mostly sunny, with a high near 93. Northeast wind 5 to 10 mph."
            },
            // ...
          ]
        }
      }
    ]
  }
}
```

## Extracting data from the API response

Back to Mailchimp's API, let's extract the list of all the email addresses from the response. These are contained under the `email_address` property on each item of the `members` list:

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
- `@underEachArrayItem`: Operate on all items from the array
- `@underEachJSONObjectProperty`: Operate on all entries from the JSON object

This GraphQL query navigates to each of the `email_address` properties, and exports their value to dynamic variable `$mailchimpListMemberEmails`:

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

Let's say that our Mailchimp subscribers also have a user in our website, and that their email address is the common ID for both applications.

We can then use the email addresses retrieved from Mailchimp (by now placed under dynamic variable `$mailchimpListMemberEmails`) to fetch the corresponding user data stored in our site:

```graphql
query GetUsersUsingMailchimpSubscriberEmails
  @depends(on: "GetDataFromMailchimp")
{
  users(filter: { searchBy: { emails: $mailchimpListMemberEmails } } ) {
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
