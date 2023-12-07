# Release Notes: 1.3

Here's a description of all the changes.

## Read `GET` variables when executing Persisted Queries via `POST`

When executing a Persisted Query via `POST`, do still accept variables passed via GET.

For instance:

```bash
curl -X POST https://mysite.com/graphql-query/register-a-newsletter-subscriber-from-instawp-to-mailchimp/?mailchimpDataCenterCode=us1&mailchimpAudienceID=88888888
```

## Improvements

- Pass data via URL params in persisted query "Register a newsletter subscriber from InstaWP to Mailchimp"

## Fixed

- Component docs displayed in the editor were not included in the plugin
