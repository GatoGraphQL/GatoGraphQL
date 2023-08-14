function graphQLFetcher(graphQLParams, headerParams) {
  let nonce = (window.graphQLByPoPGraphiQLSettings && window.graphQLByPoPGraphiQLSettings.nonce) ? window.graphQLByPoPGraphiQLSettings.nonce : null;
  let apiURL = (window.graphQLByPoPGraphiQLSettings && window.graphQLByPoPGraphiQLSettings.endpoint) ? window.graphQLByPoPGraphiQLSettings.endpoint : null;

  let headers = {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    'X-WP-Nonce': nonce
  };
  if (headerParams != null && headerParams.headers != null) {
    for (var attrname in headerParams.headers) {
      headers[attrname] = headerParams.headers[attrname];
    }
  }

  return fetch(apiURL, {
    method: 'post',
    headers: headers,
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
    headerEditorEnabled: true
  }),
  document.getElementById('graphiql')
);
