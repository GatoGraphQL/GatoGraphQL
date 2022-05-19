function graphQLFetcher(graphQLParams) {
  let nonce = (window.graphQLByPoPGraphiQLSettings && window.graphQLByPoPGraphiQLSettings.nonce) ? window.graphQLByPoPGraphiQLSettings.nonce : null;
  let apiURL = (window.graphQLByPoPGraphiQLSettings && window.graphQLByPoPGraphiQLSettings.endpoint) ? window.graphQLByPoPGraphiQLSettings.endpoint : null;

  return fetch(apiURL, {
    method: 'post',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      'X-WP-Nonce': nonce
    },
    body: JSON.stringify(graphQLParams),
    credentials: 'include',
  })
    .then(function(response) {
      return response.text();
    })
    .then(function(responseBody) {
      try {
        return JSON.parse(responseBody);
      } catch (error) {
        return responseBody;
      }
    });
}

var defaultQuery = (window.graphQLByPoPGraphiQLSettings && window.graphQLByPoPGraphiQLSettings.defaultQuery) ? window.graphQLByPoPGraphiQLSettings.defaultQuery : null;
var response = (window.graphQLByPoPGraphiQLSettings && window.graphQLByPoPGraphiQLSettings.response) ? window.graphQLByPoPGraphiQLSettings.response : null;
ReactDOM.render(
  React.createElement(GraphiQL, {
    fetcher: graphQLFetcher,
    defaultQuery: defaultQuery,
    response: response,
    headerEditorEnabled: false
  }),
  document.getElementById('graphiql')
);
