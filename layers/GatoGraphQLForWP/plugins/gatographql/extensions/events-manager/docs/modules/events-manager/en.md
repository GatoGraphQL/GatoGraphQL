# Events Manager

Integration with the [Events Manager](https://wordpress.org/plugins/events-manager/) plugin, to fetch event data.

```graphql
query {
  events {
    id
    title
    content
    startDate
    endDate
    isAllDay
    location {
      id
      name
      address
      city
      coordinates
    }
  }
}
```
<!-- 
## Bundles including extension

- [“All in One Toolbox for WordPress” Bundle](../../../../../bundle-extensions/all-feature-bundled-extensions/docs/modules/all-feature-bundled-extensions/en.md) -->
