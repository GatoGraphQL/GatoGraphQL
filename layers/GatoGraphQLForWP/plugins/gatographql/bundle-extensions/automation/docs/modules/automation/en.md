# Automation

Use GraphQL to automate tasks in your app:

- Execute queries when some event happens
- Chain queries
- Schedule and trigger queries via WP-Cron

<!-- [Watch “How to use the Automation extension” on YouTube](https://www.youtube.com/watch?v=@todo) -->

---

With the **Automation Configurator**, you can create automations via the WordPress editor, with any WordPress action hook as the trigger, and the execution of a GraphQL persisted query as the action.

<div class="img-width-1024" markdown=1>

![Automation Rule editor](../../../../../extensions/automation/docs/images/automation-rule-editor.png "Automation Rule editor")

</div>

With the **Query Resolution Action**, when the GraphQL server resolves a query, it triggers the action hook `gatographql__executed_query` with the GraphQL response, allowing the chaining of GraphQL queries.

With **WP-Cron**, action hooks are provided to trigger the execution of a GraphQL query every X amount of time:

- `gatographql__execute_query`
- `gatographql__execute_persisted_query`
