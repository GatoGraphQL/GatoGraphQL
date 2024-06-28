# MultilingualPress

Integration with the [MultilingualPress](https://multilingualpress.org/) plugin.

The GraphQL schema is provided the fields to retrieve multilingual data.

## Types `Post`, `Page`, `PostTag` and `PostCategory`

Query the language for the entity, and the IDs for the translations for that entity.

These types implement interface `MultilingualPressTranslatable`.

| Field | Description |
| --- | --- |
| `multilingualpressTranslationConnections` | Translation connections for the entity for all sites in the network, or `null` if no connection was assigned. |

Field `multilingualpressTranslationConnections` provides results of type `MultilingualPressTranslationConnection`, from which we can query the site ID and entity ID for the connection. It accepts input `includeSelf`, to indicate if to include the queried entity's connection in the results (it's `false` by default), and inputs `includeSiteIDs` and `excludeSiteIDs`, to filter the included sites in the results.

```graphql
{
  posts {
    id
    title
    multilingualpressTranslationConnections {
      ...MultilingualPressConnectionData
    }
    categories {
      id
      name
      multilingualpressTranslationConnections {
        ...MultilingualPressConnectionData
      }
    }
    tags {
      id
      name
      multilingualpressTranslationConnections {
        ...MultilingualPressConnectionData
      }
    }
  }

  pages {
    id
    title
    multilingualpressTranslationConnections {
      ...MultilingualPressConnectionData
    }
  }

  postCategories {
    name
    slug
    multilingualpressTranslationConnections {
      ...MultilingualPressConnectionData
    }
  }

  postTags {
    name
    slug
    multilingualpressTranslationConnections {
      ...MultilingualPressConnectionData
    }
  }
}

fragment MultilingualPressConnectionData {
  siteID
  entityID
}
```

## Types `GenericCustomPost`, `GenericTag` and `GenericCategory`

These types implement interface `MultilingualPressMaybeTranslatable`.

`GenericCustomPost` is a type used to represent any custom post installed on the site, such as `Portfolio`, `Event`, `Product`, or other. Similarly, `GenericTag` and `GenericCategory` are used to represent their taxonomies.

Each of these CPTs and taxonomies can be defined to be translatable on the MultilingualPress settings. Field `multilingualpressTranslationConnections` will then have the same behavior as for `Post` and the others (described above), and also return `null` if the entity's CPT or taxonomy is not configured to be translated.

In addition, field `multilingualpressIsTranslatable` indicates if the CPT or taxonomy is configured to be translatable.

| Field | Description |
| --- | --- |
| `multilingualpressTranslationConnections` | Translation connections for the entity for all sites in the network, or `null` if no connection was assigned, or if the entity is not configured to be translated (via MultilingualPress Settings). |
| `multilingualpressIsTranslatable` | Indicate if the entity can be translated. |

```graphql
{
  customPosts(filter: { customPostTypes: "some-cpt" }) {
    __typename
    title
    customPostType
    multilingualpressIsTranslatable
    multilingualpressTranslationConnections {
      ...MultilingualPressConnectionData
    }
    ...on GenericCustomPost {
      categories(taxonomy: "some-category") {
        name
        ...on GenericCategory {
          multilingualpressIsTranslatable
          multilingualpressTranslationConnections {
            ...MultilingualPressConnectionData
          }
        }
      }
      tags(taxonomy: "some-tag") {
        name
        ...on GenericTag {
          multilingualpressIsTranslatable
          multilingualpressTranslationConnections {
            ...MultilingualPressConnectionData
          }
        }
      }
    }
  }

  categories(taxonomy: "some-category") {
    __typename
    ...on GenericCategory {
      name
      slug
      taxonomy
      multilingualpressIsTranslatable
      multilingualpressTranslationConnections {
        ...MultilingualPressConnectionData
      }
    }
  }

  tags(taxonomy: "some-tag") {
    __typename
    ...on GenericTag {
      name
      slug
      taxonomy
      multilingualpressIsTranslatable
      multilingualpressTranslationConnections {
        ...MultilingualPressConnectionData
      }
    }
  }
}

fragment MultilingualPressConnectionData {
  siteID
  entityID
}
```
