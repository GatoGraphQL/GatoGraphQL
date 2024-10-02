# Polylang Integration

Integration with the <a href="https://wordpress.org/plugins/polylang/" target="_blank">Polylang</a> plugin (and also <a href="https://polylang.pro" target="_blank">Polylang PRO</a>).

When Polylang is installed in the WordPress site, fetching data using GraphQL would retrieve results for all languages. For instance, field `posts` might fetch posts in English, Spanish and French.

With the Polylang integration, fields get an extra argument `polylangLanguagesBy`, to fetch data for some specific language only:

```graphql
{
  posts(
    filter: {
      polylangLanguagesBy: {
        codes: ["en"]
      }
    }
  ) {
    title
    polylangLanguage {
      code
    }
  }

  pages(
    filter: {
      polylangLanguagesBy: {
        locales: ["en_US"]
      }
    }
  ) {
    title
    polylangLanguage {
      locale
    }
  }

  customPosts(
    filter: {
      polylangLanguagesBy: {
        predefined: DEFAULT
      }
      customPostTypes: "some-cpt"
    }
  ) {
    title
    polylangLanguage {
      code
    }
  }
}
```

<!-- ## List of bundled extensions

- [Polylang](../../../../../extensions/polylang/docs/modules/polylang/en.md) -->
