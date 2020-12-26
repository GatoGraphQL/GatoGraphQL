# Schema

This layer contains packages with definitions concerning data entities: their CMS-agnostic business logic, and their implementation for some specific CMS.

## Type of data entities

The PoP schema is a superset of the GraphQL schema, and it involves the same elements:

- types (such as posts, users, comments, etc)
- fields
- interfaces
- enums
- custom scalars
- directives

In addition, data entities may support extra features. For instance: 

- [Fields are composable](../API#composable-fields) and can be added to every type, enabling to produces [global fields](../API#operators-and-helpers)
- [Directives are composable](../API#composable-directives), so they can modify the behavior of another directive
- [Directives can accept "expressions"](../API#directive-expressions), which are dynamic variables created on runtime, during the query resolution

## CMS-agnosticism

The `Schema` layer is CMS-agnostic, so the same data entity can work on any CMS. It involves packages with the contracts, and also with the implementation of the contracts for some specific CMS. For instance, all packages ending in `-wp` provide an implementation for WordPress.

In addition, this layer is application-agnostic, independent from both the `API` and `SiteBuilder` layers: a data entity has a single source of truth of code, which can interact with any API (whether it is REST or GraphQL or any other one) or with the engine when rendering the website.

### Package architecture convention

The code for some component is divided into 2 separate packages:

- A CMS-agnostic package: it contains the business code and generic contracts, but no CMS-specific code (eg: [`posts`](packages/posts) package)
- A CMS-specific package, containing the implementation of the contracts (eg: [`posts-wp`](packages/posts-wp), implementing contracts for WordPress)
