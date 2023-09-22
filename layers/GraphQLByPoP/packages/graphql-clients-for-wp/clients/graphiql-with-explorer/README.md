# GraphiQL with Explorer client for GraphQL by PoP

## Instructions for generating the client code

Upstream from where this code was taken: https://github.com/OneGraph/graphiql-explorer-example

Run `npm run build` to generate the files under `build/`, they are taken directly from there

Using [craco](https://www.npmjs.com/package/@craco/craco) to override the webpack config for the `build` to remove chunks and the hash in the JS/CSS filenames, so the JS asset to load is always `main.js`. Solution taken from [here](https://github.com/facebook/create-react-app/issues/5306#issuecomment-695173195).

---

## Original `README`

Example usage of [OneGraph](https://www.onegraph.com)'s open source [GraphiQL explorer](https://github.com/OneGraph/graphiql-explorer).

## Setup

Install dependencies:

```
npm install
# or
yarn install
```

Start the server:

```
npm run start
# or
yarn start
```

Your browser will automatically open to http://localhost:3000 with the explorer open.

## Live demo

The example app is deployed to GitHub pages at [https://onegraph.github.io/graphiql-explorer-example/](https://onegraph.github.io/graphiql-explorer-example/).

![Preview](https://user-images.githubusercontent.com/476818/51567716-c00dfa00-1e4c-11e9-88f7-6d78b244d534.gif)
