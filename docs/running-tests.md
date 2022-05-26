# Running tests

Both unit tests and integration tests are executed together, when running:

```bash
composer test
```

## Integration tests

Integration tests are executed:

- against the Lando webserver, under `http://graphql-api.lndo.site`
- asserting some response based on the initial set of data, as imported via `graphql-api-data.xml`

When the Lando webserver is not running, integration tests are skipped.

If some entry from the DB has been edited (eg: a persisted query) and some integration test fails, you can regenerate the DB (with the initial set of data) by running:

```bash
composer reset-db
```
