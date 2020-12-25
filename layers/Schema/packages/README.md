# Schema

Definitions for all the data entities. Influenced by GraphQL, it involves the same data elements, including types (such as posts, users, comments, etc) with their fields, and directives.

The `Schema` layer is CMS-agnostic, so the same data entity can work on any CMS. It involves packages with the contracts, and also with the implementation of the contracts for some specific CMS. For instance, all packages ending in `-wp` provide an implementation for WordPress.

In addition, this layer is platform-agnostic, independent from both the `API` and `SiteBuilder` layers: the same single source of truth can power any API (whether it is REST or GraphQL or any other one), and also the creation of the website via the `SiteBuilder` layer.

## CMS-agnostic convention

The code for some component is divided into 2 separate packages:

- A CMS-agnostic package: it contains the business code and generic contracts, but no CMS-specific code (eg: [`posts`](packages/posts) package)
- A CMS-specific package, containing the implementation of the contracts (eg: [`posts-wp`](packages/posts-wp), implementing contracts for WordPress)
