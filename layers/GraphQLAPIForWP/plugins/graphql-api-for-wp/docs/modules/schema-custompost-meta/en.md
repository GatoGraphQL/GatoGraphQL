# Schema Custom Post Meta

Retrieve meta values for custom posts, by querying fields `metaValue` and `metaValues`.

For security reasons, which meta keys can be queried must be explicitly configured. By default, the list is empty.

## How to use

Query fields `metaValue` and `metaValues` on a custom post, passing the required meta key as field argument `key`.

For instance, this query retrieves the post's `_edit_last` meta value (as long as allowed by configuration):

```graphql
{
  posts {
    id
    editLast: metaValue(key: "_edit_last")
  }
}
```

## Configuring the allowed meta keys

In the "Schema Custom Post Meta" tab from the Settings, we must configure the list of meta keys that can be queried via the meta fields.

Each entry can either be:

- A regex (regular expression), if it's surrounded by `/`, or
- The full option name, otherwise

For instance, both entries `_edit_last` and `/_edit_.*/` match meta key `"_edit_last"`.

<a href="../../images/schema-configuration-custompost-meta-entries.png" target="_blank">![Defining the entries](../../images/schema-configuration-custompost-meta-entries.png "Defining the entries")</a>

There are 2 behaviors, "Allow access" and "Deny access":

👉🏽 <strong>Allow access:</strong> only the configured entries can be accessed, and no other can<br/>
👉🏽 <strong>Deny access:</strong> the configured entries cannot be accessed, all other entries can

<a href="../../images/schema-configuration-custompost-meta-behavior.png" target="_blank">![Defining the access behavior](../../images/schema-configuration-custompost-meta-behavior.png "Defining the access behavior")</a>

## Performance considerations

Fetching multiple meta keys for the same object requires a single database call.

However, every call to the database involves only 1 object.

When the query involves a large number of results, resolving the query could become slow.
