# JetEngine

Integration with Crocoblock's <a href="https://crocoblock.com/plugins/jetengine/" target="_blank">JetEngine</a> plugin.

---

The schema exposes fields to query Custom Content Type (CCT) data:

- `jetengineCCTEntries: [JetEngineCCTEntry!]!`
- `jetengineCCTEntryCount: Int!`
- `jetengineCCTEntry: JetEngineCCTEntry`

Pass the CCT slug via the `slug` argument (the CCT must be set as queryable in the plugin Settings).

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
