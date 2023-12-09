// @flow

import React, { Component } from "react";
import GraphiQL from "graphiql";
import GraphiQLExplorer from "graphiql-explorer";
import { buildClientSchema, getIntrospectionQuery, parse } from "graphql";

// import { makeDefaultArg, getDefaultScalarArgValue } from "./CustomArgs";

import "graphiql/graphiql.css";
import "./App.css";

import type { GraphQLSchema } from "graphql";

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
  window.history.replaceState(null, null, newSearch);
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
    var regex=/^\s*(true|1|on)\s*$/i
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
// // Provide "edit_schema" param either through URL or through script source
// if ((parameters.edit_schema && strToBool(parameters.edit_schema)) || (scriptParams.has('edit_schema') && strToBool(scriptParams.get('edit_schema')))) {
//   apiURL += (apiURLHasParams ? '&' : '?') + 'edit_schema=true';
//   apiURLHasParams = true;
// }
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

function fetcher(params: Object, headerParams: Object): Object {
  let headers = {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  };
  if (headerParams != null && headerParams.headers != null) {
    for (var attrname in headerParams.headers) {
      headers[attrname] = headerParams.headers[attrname];
    }
  }
  // If there is a nonce in the global object, attach it
  const nonce = (window.graphiQLWithExplorerClientForWP && window.graphiQLWithExplorerClientForWP.nonce) ? window.graphiQLWithExplorerClientForWP.nonce : null;
  if (nonce != null) {
    headers['X-WP-Nonce'] = nonce;
  }
  return fetch(
    apiURL,
    {
      method: "POST",
      headers: headers,
      body: JSON.stringify(params),
      credentials: 'include'
    }
  )
    .then(function(response) {
      return response.text();
    })
    .then(function(responseBody) {
      try {
        return JSON.parse(responseBody);
      } catch (e) {
        return responseBody;
      }
    });
}

type State = {
  schema: ?GraphQLSchema,
  query: string,
  explorerIsOpen: boolean
};

const DEFAULT_QUERY = `# Welcome to GraphiQL
#
# GraphiQL is an in-browser tool for writing, validating, and
# testing GraphQL queries.
#
# Type queries into this side of the screen, and you will see intelligent
# typeaheads aware of the current GraphQL type schema and live syntax and
# validation errors highlighted within the text.
#
# GraphQL queries typically start with a "{" character. Lines that starts
# with a # are ignored.
#
# An example GraphQL query might look like:
#
#   {
#     field(arg: "value") {
#       subField
#     }
#   }
#
# Run the query (at any moment):
#
#   Ctrl-Enter (or press the play button above)
#
`

// If passing the requested query via PHP, we can avoid the issue of the encoded query not properly handled in JS
var requestedQuery = window.graphiQLWithExplorerClientForWP ? window.graphiQLWithExplorerClientForWP.requestedQuery : null;
var defaultQuery = (window.graphiQLWithExplorerClientForWP && window.graphiQLWithExplorerClientForWP.defaultQuery) ? window.graphiQLWithExplorerClientForWP.defaultQuery : DEFAULT_QUERY;
// Watch out! Use `decodeURIComponent` because the wp-admin encodes the query,
// as in: https://gatographql.lndo.site/wp-admin/admin.php?page=gatographql&operationName=MyQuery&query=query+MyQuery+%7B++me%7D
// which then would print a query like this: query+MyQuery+{++me}
var queryDecodeURIComponent = (window.graphiQLWithExplorerClientForWP && window.graphiQLWithExplorerClientForWP.queryDecodeURIComponent) ? window.graphiQLWithExplorerClientForWP.queryDecodeURIComponent : false;
// Function taken from: https://gist.github.com/robinbb/10687275
function formURLDecodeComponent(s) {
    return decodeURIComponent((s + '').replace(/\+/g, ' '));
}
var query = requestedQuery || (queryDecodeURIComponent ? formURLDecodeComponent(parameters.query || "") : parameters.query) || defaultQuery;

class App extends Component<{}, State> {
  _graphiql: GraphiQL;
  state = { schema: null, query: query, explorerIsOpen: true };

  componentDidMount() {
    fetcher({
      query: getIntrospectionQuery(),
      operationName: ""
    }).then(result => {
      const editor = this._graphiql.getQueryEditor();
      editor.setOption("extraKeys", {
        ...(editor.options.extraKeys || {}),
        "Shift-Alt-LeftClick": this._handleInspectOperation
      });

      this.setState({ schema: buildClientSchema(result.data) });
    });
  }

  _handleInspectOperation = (
    cm: any,
    mousePos: { line: Number, ch: Number }
  ) => {
    const parsedQuery = parse(this.state.query || "");

    if (!parsedQuery) {
      console.error("Couldn't parse query document");
      return null;
    }

    var token = cm.getTokenAt(mousePos);
    var start = { line: mousePos.line, ch: token.start };
    var end = { line: mousePos.line, ch: token.end };
    var relevantMousePos = {
      start: cm.indexFromPos(start),
      end: cm.indexFromPos(end)
    };

    var position = relevantMousePos;

    var def = parsedQuery.definitions.find(definition => {
      if (!definition.loc) {
        console.log("Missing location information for definition");
        return false;
      }

      const { start, end } = definition.loc;
      return start <= position.start && end >= position.end;
    });

    if (!def) {
      console.error(
        "Unable to find definition corresponding to mouse position"
      );
      return null;
    }

    var operationKind =
      def.kind === "OperationDefinition"
        ? def.operation
        : def.kind === "FragmentDefinition"
        ? "fragment"
        : "unknown";

    var operationName =
      def.kind === "OperationDefinition" && !!def.name
        ? def.name.value
        : def.kind === "FragmentDefinition" && !!def.name
        ? def.name.value
        : "unknown";

    var selector = `.graphiql-explorer-root #${operationKind}-${operationName}`;

    var el = document.querySelector(selector);
    el && el.scrollIntoView();
  };

  _handleEditQuery = (query: string): void => {
      this.setState({ query })
      this._onEditQuery( query )
  };

  _handleToggleExplorer = () => {
    this.setState({ explorerIsOpen: !this.state.explorerIsOpen });
  };

  // When the query and variables string is edited, update the URL bar so
  // that it can be easily shared.
  _onEditQuery = (newQuery: string): void => {
    parameters.query = newQuery;
    updateURL();
  }

  _onEditVariables = (newVariables: string): void => {
    parameters.variables = newVariables;
    updateURL();
  }

  _onEditOperationName = (newOperationName: string): void => {
    parameters.operationName = newOperationName;
    updateURL();
  }

  render() {
    const { query, schema } = this.state;
    // Inject settings from the application
    var response = (window.graphiQLWithExplorerClientForWP && window.graphiQLWithExplorerClientForWP.response) ? window.graphiQLWithExplorerClientForWP.response : "Click the \"Execute Query\" button, or press Ctrl+Enter (Command+Enter in Mac)";
    return (
      <div className="graphiql-root">
        <div className="graphiql-container">
          <GraphiQLExplorer
            schema={schema}
            query={query}
            onEdit={this._handleEditQuery}
            onRunOperation={operationName =>
              this._graphiql.handleRunQuery(operationName)
            }
            explorerIsOpen={this.state.explorerIsOpen}
            onToggleExplorer={this._handleToggleExplorer}
            // getDefaultScalarArgValue={getDefaultScalarArgValue}
            // makeDefaultArg={makeDefaultArg}
          />
          <GraphiQL
            ref={ref => (this._graphiql = ref)}
            fetcher={fetcher}
            schema={schema}
            query={query}
            onEditQuery={this._handleEditQuery}
            onEditVariables={this._onEditVariables}
            onEditOperationName={this._onEditOperationName}
            response={response}
            headerEditorEnabled={true}
          >
            <GraphiQL.Toolbar>
              <GraphiQL.Button
                onClick={() => this._graphiql.handlePrettifyQuery()}
                label="Prettify"
                title="Prettify Query (Shift-Ctrl-P)"
              />
              <GraphiQL.Button
                onClick={() => this._graphiql.handleToggleHistory()}
                label="History"
                title="Show History"
              />
              <GraphiQL.Button
                onClick={this._handleToggleExplorer}
                label="Explorer"
                title="Toggle Explorer"
              />
            </GraphiQL.Toolbar>
          </GraphiQL>
        </div>
      </div>
    );
  }
}

export default App;
