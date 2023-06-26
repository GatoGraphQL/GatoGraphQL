# Events Manager

Integration with the <a href="https://wordpress.org/plugins/events-manager/" target="_blank">Events Manager</a> plugin, to fetch event data.

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
