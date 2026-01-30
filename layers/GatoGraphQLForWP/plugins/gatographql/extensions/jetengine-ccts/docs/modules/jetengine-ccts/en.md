# JetEngine Custom Content Types (CCTs)

Integration with Crocoblock's [JetEngine](https://crocoblock.com/plugins/jetengine/) plugin, to fetch Custom Content Type (CCT) data.

The schema exposes the following Root fields:

- `jetengineCCTEntries(slug: String!): [JetEngineCCTEntry!]!`
- `jetengineCCTEntryCount(slug: String!): Int`
- `jetengineCCTEntry(slug: String!, id: ID!): JetEngineCCTEntry`

---

On `JetEngineCCTEntry`, we query field values via:

- `fieldValues` is a JSON object with all CCT fields for that entry
- `fieldValue(slug)` to query a single field by slug

The values are cast to the corresponding type (`text` => string, `number` => int, `media ID` => int, `gallery` => array of ints, etc).

## Examples

**List entries:**

```graphql
query {
  jetengineCCTEntries(slug: "sample_cct") {
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
  jetengineCCTEntry(slug: "sample_cct", id: 1) {
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

**Count entries (optionally with filter):**

```graphql
query {
  jetengineCCTEntryCount(slug: "sample_cct")
}
```

**List with filter, pagination, and sort:**

List queries support `filter`, `pagination`, and `sort` args.

```graphql
query {
  jetengineCCTEntries(
    slug: "sample_cct"
    filter: { search: [{ field: "cct_author_id", value: 1, operator: EQUALS }] }
    pagination: { limit: 10, offset: 0 }
    sort: { orderBy: "cct_created", order: DESC }
  ) {
    id
    authorID
  }
}
```
