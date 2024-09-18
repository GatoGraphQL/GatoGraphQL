# User Meta

Retrieve meta values for users, by querying fields `metaValue` and `metaValues`.

For security reasons, which meta keys can be queried must be explicitly configured. By default, the list is empty.

## Description

Query fields `metaValue` and `metaValues` on a user, passing the required meta key as field argument `key`.

For instance, this query retrieves the user's `last_name` meta value (as long as allowed by configuration):

```graphql
{
  users {
    id
    lastName: metaValue(key: "last_name")
  }
}
```

## Configuring the allowed meta keys

We must configure the list of meta keys that can be queried via the meta fields.

Each entry can either be:

- A regex (regular expression), if it's surrounded by `/` or `#`, or
- The full meta key, otherwise

For instance, any of these entries match meta key `"last_name"`:

- `last_name`
- `/last_.*/`
- `#last_([a-zA-Z]*)#`

There are 2 places where this configuration can take place, in order of priority:

1. Custom: In the corresponding Schema Configuration
2. General: In the Settings page

In the Schema Configuration applied to the endpoint, select option `"Use custom configuration"` and then input the desired entries:

<div class="img-width-1024" markdown=1>

![Defining the entries in the Schema Configuration](../../images/schema-configuration-user-meta-entries.png "Defining the entries in the Schema Configuration")

</div>

Otherwise, the entries defined in the "User Meta" tab from the Settings will be used:

<div class="img-width-1024" markdown=1>

![Defining the entries in the Settings](../../images/settings-user-meta-entries.png "Defining the entries in the Settings")

</div>

There are 2 behaviors, "Allow access" and "Deny access":

- **Allow access:** only the configured entries can be accessed, and no other can
- **Deny access:** the configured entries cannot be accessed, all other entries can

<div class="img-width-1024" markdown=1>

![Defining the access behavior](../../images/schema-configuration-user-meta-behavior.png "Defining the access behavior")

</div>

## Performance considerations

Fetching multiple meta keys for the same object requires a single database call.

However, every call to the database involves only 1 object.

When the query involves a large number of results, resolving the query could become slow.
