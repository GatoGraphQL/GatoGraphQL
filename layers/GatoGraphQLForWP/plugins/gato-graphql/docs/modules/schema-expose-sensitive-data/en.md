# Expose Sensitive Data in the Schema

Expose “sensitive” data elements in the GraphQL schema, which provide access to potentially private user data.

The GraphQL schema must strike a balance between public and private elements (including fields and input fields), as to avoid exposing private information in a public API.

For instance, to access post data, we have field `Root.posts`, which by default can only retrieve published posts. With this module, a new option `Expose Sensitive Data in the Schema` is added to the Schema Configuration. When enabled, argument `filter` in `Root.posts` exposes an additional input `status`, enabling to retrieve non-published posts (eg: posts with status `"draft"`), which is private data.

## List of “sensitive” data elements

By default, the following data elements are treated as “sensitive” (they can also be configured as “normal” in the Settings page for the corresponding module; see below):

**User:**

- `email`
- `roles`
- `capabilities`

**Custom Posts:**

- `status`
- `hasPassword`
- `password`

**Comments:**

- `status`

## Inspecting the “sensitive” data elements via schema introspection

The `isSensitiveDataElement` property is added to field `extensions` when doing schema introspection. To find out which are the “sensitive” data elements from the schema, execute this query:

```graphql
query ViewSensitiveDataElements {
  __schema {
    types {
      name
      fields {
        name
        extensions {
          isSensitiveDataElement
        }
        args {
          name
          extensions {
            isSensitiveDataElement
          }
        }
      }
      inputFields {
        name
        extensions {
          isSensitiveDataElement
        }
      }
      enumValues {
        name
        extensions {
          isSensitiveDataElement
        }
      }
    }
  }
}
```

And then search for entries with `"isSensitiveDataElement": true` in the results.

## Overriding the default configuration

The elements listed above can be made public.

In the Settings page, in the corresponding tab for each, there is a checkbox to configure if to treat them as “sensitive” or “normal”:

![Settings to treat user email as “sensitive” data](../../images/settings-treat-user-email-as-sensitive-data.png)

## How to use

Exposing “sensitive” data elements in the schema can be configured as follows, in order of priority:

✅ Specific mode for the custom endpoint or persisted query, defined in the schema configuration

![Adding sensitive fields to the schema, set in the Schema configuration](../../images/schema-configuration-adding-sensitive-fields-to-schema.png "Adding sensitive fields to the schema, set in the Schema configuration")

✅ Default mode, defined in the Settings

If the schema configuration has value `"Default"`, it will use the mode defined in the Settings:

![Expose Sensitive Data in the Schema, in the Settings](../../images/settings-schema-expose-sensitive-data-default.png "Expose Sensitive Data in the Schema, in the Settings")

### Adding “sensitive” data elements to the Admin clients

In the Settings, we can select to add the “sensitive” data elements to the wp-admin's GraphiQL and Interactive Schema clients:

![Expose Sensitive Data in the admin clients, in the Settings](../../images/settings-schema-expose-sensitive-data-for-admin.png "Expose Sensitive Data in the admin clients, in the Settings")

## When to use

Use whenever exposing private information is allowed, such as when building a static website, fetching data from a local WordPress instance (i.e. not a public API).
