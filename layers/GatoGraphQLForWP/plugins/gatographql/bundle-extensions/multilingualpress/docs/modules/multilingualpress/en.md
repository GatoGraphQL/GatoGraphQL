# MultilingualPress

Integration with the <a href="https://multilingualpress.org/" target="_blank">MultilingualPress</a> plugin.

<!-- [Watch “How to use the MultilingualPress extension” on YouTube](https://www.youtube.com/watch?v=@todo) -->

---

The GraphQL schema is provided the fields to retrieve multilingual data.

```graphql
{
  posts {
    multilingualpressTranslationConnections {
      ...MultilingualPressConnectionData
    }
    categories {
      multilingualpressTranslationConnections {
        ...MultilingualPressConnectionData
      }
    }
    tags {
      multilingualpressTranslationConnections {
        ...MultilingualPressConnectionData
      }
    }
  }

  pages {
    multilingualpressTranslationConnections {
      ...MultilingualPressConnectionData
    }
  }

  postCategories {
    multilingualpressTranslationConnections {
      ...MultilingualPressConnectionData
    }
  }

  postTags {
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
