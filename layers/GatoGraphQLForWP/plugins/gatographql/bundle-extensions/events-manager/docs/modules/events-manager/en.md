# Events Manager

Integration with the <a href="https://wordpress.org/plugins/events-manager/" target="_blank">Events Manager</a> plugin.

<!-- [Watch “How to use the Events Manager extension” on YouTube](https://www.youtube.com/watch?v=@todo) -->

---

The GraphQL schema is provided the fields to retrieve event data.

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
