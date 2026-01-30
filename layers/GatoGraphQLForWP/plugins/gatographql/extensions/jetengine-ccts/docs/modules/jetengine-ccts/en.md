# JetEngine Custom Content Types (CCTs)

Integration with Crocoblock's [JetEngine](https://crocoblock.com/plugins/jetengine/) plugin, to fetch [Custom Content Type (CCT)](https://crocoblock.com/knowledge-base/features/custom-content-type/) data.

The schema exposes the following Root fields:

- `jetengineCCTEntries: [JetEngineCCTEntry!]!`
- `jetengineCCTEntryCount: Int!`
- `jetengineCCTEntry: JetEngineCCTEntry`

---

On `JetEngineCCTEntry`, we query field values via:

- `fieldValues` is a JSON object with all CCT fields for that entry
- `fieldValue(slug)` to query a single field by slug

Values are cast to the [field type defined in the CCT](https://crocoblock.com/knowledge-base/features/meta-field-types-overview/): `fieldValue(slug)` and each key in `fieldValues` return the right shape (e.g. text → string, number → int, media ID → int, gallery → array of IDs).

## Examples

**List entries:**

```graphql
query {
  jetengineCCTEntries(slug: "some_cct_slug") {
    id
    uniqueID
    cctSlug
    status
    createdDate
    modifiedDate
    authorID
    author {
      id
      name
    }
    singleCustomPostID
    singleCustomPost {
      id
      title
    }
    fieldValues
    someField: fieldValue(slug: "some_field_slug")
  }
}
```

**Single entry by slug and ID:**

```graphql
query {
  jetengineCCTEntry(slug: "some_cct_slug", id: 1) {
    id
    uniqueID
    cctSlug
    status
    createdDate
    modifiedDate
    author {
      id
      name
    }
    singleCustomPost {
      id
      title
    }
    fieldValues
    someField: fieldValue(slug: "some_field_slug")
  }
}
```

**List and count with filter, pagination, and sort:**

List queries accept `filter`, `pagination`, and `sort`; count accepts `filter`.

```graphql
query {
  jetengineCCTEntryCount(
    slug: "some_cct_slug"
    filter: { search: [{ field: "cct_author_id", value: 1, operator: EQUALS }] }
  )
  jetengineCCTEntries(
    slug: "some_cct_slug"
    filter: { search: [{ field: "cct_author_id", value: 1, operator: EQUALS }] }
    pagination: { limit: 10, offset: 0 }
    sort: { by: "cct_created", order: DESC }
  ) {
    id
    authorID
  }
}
```
