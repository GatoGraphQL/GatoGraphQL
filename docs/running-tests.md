# Running tests

## Unit tests

Run:

```bash
composer unit-test
```

## Integration tests

Run:

```bash
composer integration-test
```

Integration tests are executed:

- against the Lando webserver, under `https://gatographql.lndo.site`
- asserting some response based on the initial set of data, as imported via `gatographql-data.xml`

When the Lando webserver is not running, integration tests are skipped.

If the data in the WordPress DB is different from the initial data set, integration tests may fail. In that case, regenerate the DB (with the initial set of data) by running:

```bash
composer reset-db
```
