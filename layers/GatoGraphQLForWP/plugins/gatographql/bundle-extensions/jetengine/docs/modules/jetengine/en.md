# JetEngine

Integration with Crocoblock's <a href="https://crocoblock.com/plugins/jetengine/" target="_blank">JetEngine</a> plugin.

---

The schema exposes fields to query Custom Content Type (CCT) data:

- `jetengineCCTEntries(slug: String!): [JetEngineCCTEntry!]!`
- `jetengineCCTEntryCount(slug: String!): Int`
- `jetengineCCTEntry(slug: String!, id: ID!): JetEngineCCTEntry`

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