
function introspectionProvider(query) {
  let nonce = (window.graphQLByPoPGraphiQLSettings && window.graphQLByPoPGraphiQLSettings.nonce) ? window.graphQLByPoPGraphiQLSettings.nonce : null;
  let apiURL = (window.graphQLByPoPGraphiQLSettings && window.graphQLByPoPGraphiQLSettings.endpoint) ? window.graphQLByPoPGraphiQLSettings.endpoint : null;
  return fetch(apiURL, {
    method: 'post',
    headers: {
      'Content-Type': 'application/json',
      'X-WP-Nonce': nonce
    },
    body: JSON.stringify({query: query}),
  }).then(response => response.json());
}

GraphQLVoyager.init(document.getElementById('voyager'), {
  introspection: introspectionProvider
})
