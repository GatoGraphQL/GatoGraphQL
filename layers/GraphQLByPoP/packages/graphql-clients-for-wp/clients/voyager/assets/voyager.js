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
  apiURL += (apiURLHasParams ? '&' : '?') + 'versionConstraint=' + (parameters.versionConstraint || scriptParams.get('versionConstraint'));
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

function introspectionProvider(query) {
  return fetch(apiURL, {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({query: query, operationName: ""}),
  }).then(response => response.json());
}

// Render <Voyager />
GraphQLVoyager.init(document.getElementById('voyager'), {
  introspection: introspectionProvider
})
