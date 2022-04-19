/**
 * This GraphiQL example illustrates how to use some of GraphiQL's props
 * in order to enable reading and updating the URL parameters, making
 * link sharing of queries a little bit easier.
 *
 * This is only one example of this kind of feature, GraphiQL exposes
 * various React params to enable interesting integrations.
 */

// Parse the search string to get url parameters.
var search = window.location.search;
var parameters = {};
search
  .substr(1)
  .split('&')
  .forEach(function(entry) {
    var eq = entry.indexOf('=');
    if (eq >= 0) {
      parameters[decodeURIComponent(entry.slice(0, eq))] = decodeURIComponent(
        entry.slice(eq + 1),
      );
    }
  });

// If variables was provided, try to format it.
if (parameters.variables) {
  try {
    parameters.variables = JSON.stringify(
      JSON.parse(parameters.variables),
      null,
      2,
    );
  } catch (e) {
    // Do nothing, we want to display the invalid JSON as a string, rather
    // than present an error.
  }
}

// When the query and variables string is edited, update the URL bar so
// that it can be easily shared.
function onEditQuery(newQuery) {
  parameters.query = newQuery;
  updateURL();
}

function onEditVariables(newVariables) {
  parameters.variables = newVariables;
  updateURL();
}

function onEditOperationName(newOperationName) {
  parameters.operationName = newOperationName;
  updateURL();
}

function updateURL() {
  var newSearch =
    '?' +
    Object.keys(parameters)
      .filter(function(key) {
        return Boolean(parameters[key]);
      })
      .map(function(key) {
        return (
          encodeURIComponent(key) + '=' + encodeURIComponent(parameters[key])
        );
      })
      .join('&');
  history.replaceState(null, null, newSearch);
}

/*
 * Converts a string to a bool.
 *
 * This conversion will:
 *
 *  - match 'true', 'on', or '1' as true.
 *  - ignore all white-space padding
 *  - ignore capitalization (case).
 *
 * '  tRue  ','ON', and '1   ' will all evaluate as true.
 *
 * Taken from https://stackoverflow.com/a/264180
 * 
 */
function strToBool(s)
{
    // will match one and only one of the string 'true','1', or 'on' rerardless
    // of capitalization and regardless off surrounding white-space.
    //
    regex=/^\s*(true|1|on)\s*$/i
    return regex.test(s);
}

/**
 * We can specify the endpoint through the included script, if it has param "endpoint"
 * Or default to "/api/graphql/"
 */
var getScriptURL = (function() {
  var scripts = document.getElementsByTagName('script');
  var index = scripts.length - 1;
  var myScript = scripts[index];
  return function() { return myScript.src; };
})();
const scriptURL = new URL(getScriptURL());
const scriptParams = new URLSearchParams(scriptURL.search);
let apiURL = scriptParams.has('endpoint') ? scriptParams.get('endpoint') : '/api/graphql/';
let apiURLHasParams = apiURL.indexOf('?') !== -1;
// Provide "use_namespace" param either through URL or through script source
if ((parameters.use_namespace && strToBool(parameters.use_namespace)) || (scriptParams.has('use_namespace') && strToBool(scriptParams.get('use_namespace')))) {
  apiURL += (apiURLHasParams ? '&' : '?') + 'use_namespace=true';
  apiURLHasParams = true;
}
// Provide "versionConstraint" param either through URL or through script source
if (parameters.versionConstraint || scriptParams.has('versionConstraint')) {
  apiURL += (apiURLHasParams ? '&' : '?') + 'versionConstraint='+(parameters.versionConstraint || scriptParams.get('versionConstraint'));
  apiURLHasParams = true;
}
// Provide "show_logs" param either through URL or through script source
if ((parameters.show_logs && strToBool(parameters.show_logs)) || (scriptParams.has('show_logs') && strToBool(scriptParams.get('show_logs')))) {
  apiURL += (apiURLHasParams ? '&' : '?') + 'actions[]=show-logs';
  apiURLHasParams = true;
}
// Provide "edit_schema" param either through URL or through script source
if ((parameters.edit_schema && strToBool(parameters.edit_schema)) || (scriptParams.has('edit_schema') && strToBool(scriptParams.get('edit_schema')))) {
  apiURL += (apiURLHasParams ? '&' : '?') + 'edit_schema=true';
  apiURLHasParams = true;
}
// Provide "mutation_scheme" param either through URL or through script source
if (parameters.mutation_scheme || scriptParams.has('mutation_scheme')) {
  apiURL += (apiURLHasParams ? '&' : '?') + 'mutation_scheme=' + (parameters.mutation_scheme || scriptParams.get('mutation_scheme'));
  apiURLHasParams = true;
}
// Provide "fieldVersionConstraints[]" param either through URL or through script source
let fieldVersionConstraints = {};
Object.keys(parameters).filter(key => key.startsWith('fieldVersionConstraints[')).forEach(function(key) {
  fieldVersionConstraints[key] = parameters[key];
})
for (var pair of scriptParams.entries()) {
  if (pair[0].startsWith('fieldVersionConstraints[')) {
    fieldVersionConstraints[pair[0]] = pair[1];
  }
}
if (Object.keys(fieldVersionConstraints).length) {
  Object.keys(fieldVersionConstraints).forEach(function(key) {
    apiURL += (apiURLHasParams ? '&' : '?') + key + '=' + fieldVersionConstraints[key];
    apiURLHasParams = true;
  })
}
// Provide "directiveVersionConstraints[]" param either through URL or through script source
let directiveVersionConstraints = {};
Object.keys(parameters).filter(key => key.startsWith('directiveVersionConstraints[')).forEach(function(key) {
  directiveVersionConstraints[key] = parameters[key];
})
for (var pair of scriptParams.entries()) {
  if (pair[0].startsWith('directiveVersionConstraints[')) {
    directiveVersionConstraints[pair[0]] = pair[1];
  }
}
if (Object.keys(directiveVersionConstraints).length) {
  Object.keys(directiveVersionConstraints).forEach(function(key) {
    apiURL += (apiURLHasParams ? '&' : '?') + key + '=' + directiveVersionConstraints[key];
    apiURLHasParams = true;
  })
}

// Defines a GraphQL fetcher using the fetch API. You're not required to
// use fetch, and could instead implement graphQLFetcher however you like,
// as long as it returns a Promise or Observable.
function graphQLFetcher(graphQLParams) {
  return fetch(apiURL, {
    method: 'post',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
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

// Render <GraphiQL /> into the body.
// See the README in the top level of this module to learn more about
// how you can customize GraphiQL by providing different values or
// additional child elements.
ReactDOM.render(
  React.createElement(GraphiQL, {
    fetcher: graphQLFetcher,
    query: parameters.query,
    variables: parameters.variables,
    operationName: parameters.operationName,
    onEditQuery: onEditQuery,
    onEditVariables: onEditVariables,
    defaultVariableEditorOpen: true,
    docExplorerOpen: true,
    headerEditorEnabled: false,
    onEditOperationName: onEditOperationName,
    response: "Click the \"Execute Query\" button, or press Ctrl+Enter (Command+Enter in Mac)"
  }),
  document.getElementById('graphiql'),
);
