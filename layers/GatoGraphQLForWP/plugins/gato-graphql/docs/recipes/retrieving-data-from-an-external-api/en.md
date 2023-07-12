# Retrieving data from an external API

Mailchimp API: https://mailchimp.com/developer/marketing/docs/fundamentals/

Query to retrieve List members:

```graphql
{
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

Response

```json
{
  "data": {
    "_sendHTTPRequest": {
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
        "total_items": 4927
      }
    }
  }
}
```
