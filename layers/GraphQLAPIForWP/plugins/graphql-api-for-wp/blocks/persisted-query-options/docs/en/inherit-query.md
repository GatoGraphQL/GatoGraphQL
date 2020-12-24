Use the GraphQL query defined in the ancestor persisted query.

For instance, if the same query must grant different access to users if it is executed from the website or from the mobile app, you can define the unique GraphQL query on a parent persisted query, inherit from it through persisted queries `mobile-app` and `website`, and then set different schema configurations on them to customize their Access Control.
