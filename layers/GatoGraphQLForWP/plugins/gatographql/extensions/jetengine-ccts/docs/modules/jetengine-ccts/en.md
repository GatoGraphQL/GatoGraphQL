# JetEngine Custom Content Types

Integration with Crocoblock's [JetEngine](https://crocoblock.com/plugins/jetengine/) plugin, to fetch Custom Content Type (CCT) data.

The schema exposes the following Root fields:

- **`jetengineCCTEntries`** (list)
- **`jetengineCCTEntryCount`** (count)
- **`jetengineCCTEntry`** (single entry by ID)

These fields receive a `slug` argument to indicate the CCT slug, which must be set as queryable in the Settings.

List queries support `filter`, `pagination`, and `sort`.

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
    someField: fieldValue(slug: "your_field_slug")
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
    someField: fieldValue(slug: "your_field_slug")
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
