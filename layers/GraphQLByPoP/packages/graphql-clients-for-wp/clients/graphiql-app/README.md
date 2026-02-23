# GraphiQL app for WordPress (GraphiQL v5)

React app that powers the admin GraphiQL client. Uses [GraphiQL v5](https://github.com/graphql/graphiql/tree/main/packages/graphiql) (Monaco Editor, plugins, theming).

## Build

After cloning or changing dependencies:

```bash
npm install
npm run build
```

The build output in `build/` is loaded by the Gato GraphQL plugin via `asset-manifest.json`. Commit the `build/` folder so that Composer installs have the assets without requiring Node.

## Dependencies

- `graphql-ws` is required for GraphiQL v5’s internal toolkit (used by Doc Explorer and History plugins) even when subscriptions are not used.
