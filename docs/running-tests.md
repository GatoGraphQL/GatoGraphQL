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

If some entry from the DB has been edited (eg: a persisted query) and some integration test fails, you can delete that entry, and regenerate it by running:

```bash
composer import-data
```

To import all the data again into the DB, you can first drop all tables from the WordPress DB (or destroy the database service in Lando), and then re-install the site and import all data:

```bash
composer install-site
```
