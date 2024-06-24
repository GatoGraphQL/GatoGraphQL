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
