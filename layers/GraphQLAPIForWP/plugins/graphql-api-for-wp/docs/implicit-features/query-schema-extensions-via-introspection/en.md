# Query schema extensions via introspection

Custom metadata attached to schema elements can be queried via field `extensions`, and every element in the schema can define its own set of properties to query.

## Description

All introspection elements of the schema have an `extensions` field available, each of them returning an object of a corresponding "`_Extensions`" type, which exposes the custom properties for that element.

Using SDL (Schema Definition Language) to visualize it, the schema offers these extra fields (and additional custom fields can be further added):

```graphql
########################################################
# Using "_" instead of "__" in introspection type name
# to avoid errors in graphql-js
########################################################

type _SchemaExtensions {
  # Is the schema being namespaced?
  isNamespaced: Boolean!
}

extend type __Schema {
  extensions: _SchemaExtensions!
}

type _NamedTypeExtensions {
  # The type name
  elementName: String!

  # The "namespaced" type name
  namespacedName: String!
}

extend type __Type {
  # Non-null for named types, null for wrapping types (Non-Null and List)
  extensions: _NamedTypeExtensions
}

type _DirectiveExtensions {
  # If no objects are returned in the field (eg: because they failed validation), does the directive still need to be executed?
  needsDataToExecute: Boolean!
}

extend type __Directive {
  extensions: _DirectiveExtensions!
}

type _FieldExtensions {
  isGlobal: Boolean!

  # Useful for nested mutations
  isMutation: Boolean!

  # `true` => Only exposed when "Expose “sensitive” data elements" is enabled
  isSensitiveDataElement: Boolean!
}

extend type __Field {
  extensions: _FieldExtensions!
}

type _InputValueExtensions {
  isSensitiveDataElement: Boolean!
}

extend type __InputValue {
  extensions: _InputValueExtensions!
}

type _EnumValueExtensions {
  isSensitiveDataElement: Boolean!
}

extend type __EnumValue {
  extensions: _EnumValueExtensions!
}
```

For instance, we can query the name and namespaced name of each type:

```graphql
query TypeQualifiedNames {
  __schema {
    types {
      name
      extensions {
        elementName
        namespacedName
      }
    }
  }
}
```

## GraphQL spec

This functionality is not yet part of the GraphQL spec, but it is expected to be added soon:

- <a href="https://github.com/graphql/graphql-spec/issues/300" target="_blank">Issue #300 - Expose user-defined meta-information via introspection API in form of directives</a>
- <a href="https://github.com/graphql/graphql-wg/discussions/1096" target="_blank">Discussion #1096 - "Metadata Directives" Proposal</a>
