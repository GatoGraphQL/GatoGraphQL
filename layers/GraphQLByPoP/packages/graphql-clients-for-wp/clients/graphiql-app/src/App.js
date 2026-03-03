import React from 'react';
import { GraphiQL } from 'graphiql';

/**
 * Custom fetcher for WordPress: adds X-WP-Nonce and credentials.
 * GraphiQL v5 accepts any function that returns a Promise<ExecutionResult>.
 */
function createFetcher() {
  const settings = window.graphQLByPoPGraphiQLSettings || {};
  const url = settings.endpoint || '/api/graphql/';
  const nonce = settings.nonce || null;

  return (graphQLParams, fetcherOpts) => {
    const headers = {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      ...(nonce && { 'X-WP-Nonce': nonce }),
    };
    if (fetcherOpts?.headers) {
      Object.assign(headers, fetcherOpts.headers);
    }
    return fetch(url, {
      method: 'POST',
      headers,
      body: JSON.stringify(graphQLParams),
      credentials: 'include',
    })
      .then((res) => res.text())
      .then((body) => {
        try {
          return JSON.parse(body);
        } catch {
          return body;
        }
      });
  };
}

export default function App() {
  const settings = window.graphQLByPoPGraphiQLSettings || {};
  const initialQuery = settings.defaultQuery || undefined;

  return (
    <GraphiQL
      fetcher={createFetcher()}
      initialQuery={initialQuery}
      defaultVariableEditorOpen={false}
    />
  );
}
