# Release Notes: 2.6

## Improvements

- Added GraphQL variable `$translateFromLanguage` to persisted query "[PRO] Translate posts for Polylang (Gutenberg)" ([#2694](https://github.com/GatoGraphQL/GatoGraphQL/pull/2694))

### Added `siteURL` field ([#2697](https://github.com/GatoGraphQL/GatoGraphQL/pull/2697))

Added the following field to the GraphQL schema, via the "Site" module:

- `Root.siteURL`

For instance, executing the following query:

```graphql
{
  siteURL
}
```

...will produce:

```json
{
  "data": {
    "siteURL": "https://mysite.com"
  }
}
```

### Added fields to fetch multisite data ([#2698](https://github.com/GatoGraphQL/GatoGraphQL/pull/2698))

The GraphQL schema now supports fetching data from a WordPress multisite network, provided via the new "Multisite" module.

This module adds the following fields to the GraphQL schema:

- `Root.networkSites`
- `Root.networkSiteCount`

Field `networkSites` returns an array with all the sites in the network, each of the new `NetworkSite` type, which contains the following fields:

- `id`
- `name`
- `url`
- `locale`
- `language`

These fields are only enabled when multisite is enabled (i.e. when method `is_multisite()` returns `true`).

For instance, executing the following query:

```graphql
{
  networkSiteCount
  networkSites {
    id
    name
    url
    locale
    language
  }
}
```

might return:

```json
{
  "data": {
    "networkSiteCount": 3,
    "networkSites": [
      {
        "id": 1,
        "name": "Site in English",
        "url": "https://mymultisite.com",
        "locale": "en_US",
        "language": "en"
      },
      {
        "id": 2,
        "name": "Site in Spanish",
        "url": "https://es.mymultisite.com",
        "locale": "es_AR",
        "language": "es"
      },
      {
        "id": 3,
        "name": "Site in French",
        "url": "https://fr.mymultisite.com",
        "locale": "fr_FR",
        "language": "fr"
      }
    ]
  }
}
```
