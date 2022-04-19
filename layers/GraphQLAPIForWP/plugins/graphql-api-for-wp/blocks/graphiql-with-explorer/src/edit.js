import React, { Component } from "react";
import GraphiQL from "graphiql";
import GraphiQLExplorer from "graphiql-explorer";
import { buildClientSchema, getIntrospectionQuery, parse } from "graphql";

import "graphiql/graphiql.css";
import '../../graphiql/src/graphiql-restore.scss';
import '../../graphiql/src/editor.scss';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import { __ } from '@wordpress/i18n';

function fetcher(params) {
	return fetch(
		window.graphqlApiGraphiql.endpoint,
		{
			method: "POST",
			headers: {
				Accept: "application/json",
				"Content-Type": "application/json",
				'X-WP-Nonce': window.graphqlApiGraphiql.nonce
			},
			body: JSON.stringify(params)
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

// const DEFAULT_QUERY = `# Welcome to GraphiQL
// #
// # GraphiQL is an in-browser tool for writing, validating, and
// # testing GraphQL queries.
// #
// # Type queries into this side of the screen, and you will see intelligent
// # typeaheads aware of the current GraphQL type schema and live syntax and
// # validation errors highlighted within the text.
// #
// # GraphQL queries typically start with a "{" character. Lines that starts
// # with a # are ignored.
// #
// # An example GraphQL query might look like:
// #
// #     {
// #       field(arg: "value") {
// #         subField
// #       }
// #     }
// #
// # Keyboard shortcuts:
// #
// #  Prettify Query:  Shift-Ctrl-P (or press the prettify button above)
// #
// #     Merge Query:  Shift-Ctrl-M (or press the merge button above)
// #
// #       Run Query:  Ctrl-Enter (or press the play button above)
// #
// #   Auto Complete:  Ctrl-Space (or just start typing)
// #
// `;

class GraphiQLWithExplorer extends Component {

	constructor(props) {
		super(props);
		this._graphiql = null;
		this.state = {
			schema: null,
			explorerIsOpen: true,
		};
		this._handleEditQuery = this._handleEditQuery.bind(this);
		this._handleEditVariables = this._handleEditVariables.bind(this);
		this._handleToggleExplorer = this._handleToggleExplorer.bind(this);
	}

	componentDidMount() {
		fetcher({
			query: getIntrospectionQuery()
		}).then(result => {
			const editor = this._graphiql.getQueryEditor();
			editor.setOption("extraKeys", {
				...(editor.options.extraKeys || {}),
				"Shift-Alt-LeftClick": this._handleInspectOperation
			});

			this.setState( { schema: buildClientSchema(result.data) } );
		});
	}

	_handleInspectOperation(
		cm,
		mousePos
	) {
		const {
			attributes: { query },
		} = this.props;
		const parsedQuery = parse(/*this.state.query*/query || "");

		if (!parsedQuery) {
			console.error(__('Couldn\'t parse query document', 'graphql-api'));
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

	_handleEditQuery( query ) {
		// Synchronize state for the Explorer
		// this.setState({ query });
		// Save state to Gutenberg
		this.props.setAttributes({ query });
	}

	_handleEditVariables( variables ) {
		// Save state to Gutenberg
		this.props.setAttributes({ variables });
	}

	_handleToggleExplorer() {
		this.setState({ explorerIsOpen: !this.state.explorerIsOpen });
	}

	render() {
		// State for the Explorer
		const { /*query,*/ schema } = this.state;
		// State from Gutenberg
		const {
			attributes: { query, variables },
			className,
		} = this.props;
		return (
			<div className={ className }>
				<div className="graphiql-container">
					<GraphiQLExplorer
						schema={ schema }
						query={ query }
						variables={ variables }
						onEdit={ this._handleEditQuery }
						onRunOperation={ operationName =>
							this._graphiql.handleRunQuery( operationName )
						}
						explorerIsOpen={ this.state.explorerIsOpen }
						onToggleExplorer={ this._handleToggleExplorer }
					/>
					<GraphiQL
						ref={ ref => ( this._graphiql = ref ) }
						fetcher={ fetcher }
						schema={ schema }
						query={ query }
						variables={ variables }
						onEditQuery={ this._handleEditQuery }
						onEditVariables={ this._handleEditVariables }
						docExplorerOpen={ false }
						headerEditorEnabled={ false }
					>
						<GraphiQL.Toolbar>
							<GraphiQL.Button
								onClick={ () => this._graphiql.handlePrettifyQuery() }
								label={ __('Prettify', 'graphql-api') }
								title={ __('Prettify Query (Shift-Ctrl-P)', 'graphql-api') }
							/>
							<GraphiQL.Button
								onClick={ () => this._graphiql.handleCopyQuery() }
								title={ __('Copy Query (Shift-Ctrl-C)', 'graphql-api') }
								label={ __('Copy', 'graphql-api') }
							/>
							<GraphiQL.Button
								onClick={ () => this._graphiql.handleToggleHistory() }
								label={ __('History', 'graphql-api') }
								title={ __('Show History', 'graphql-api') }
							/>
							<GraphiQL.Button
								onClick={ this._handleToggleExplorer }
								label={ __('Explorer', 'graphql-api') }
								title={ __('Toggle Explorer', 'graphql-api') }
							/>
						</GraphiQL.Toolbar>
					</GraphiQL>
				</div>
			</div>
		);
	}
}

export default GraphiQLWithExplorer;
