import GraphiQL from 'graphiql';
import fetch from 'isomorphic-fetch';
import 'graphiql/graphiql.css';

/**
 * Adding the GraphiQL client inside Gutenberg has styles
 * overriden. Undo the undesired changes
 */
import './graphiql-restore.scss';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

const graphQLFetcher = ( graphQLParams ) => {
	return fetch( window.graphqlApiGraphiql.endpoint, {
		method: 'post',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
			'X-WP-Nonce': window.graphqlApiGraphiql.nonce
		},
		body: JSON.stringify( graphQLParams ),
	} ).then( ( response ) => response.json() );
}

const EditBlock = ( props ) => {
	const {
		attributes: { query, variables },
		setAttributes,
		className,
	} = props;
	const onEditQuery = ( newValue ) =>
		setAttributes( { query: newValue } );
	const onEditVariables = ( newValue ) =>
		setAttributes( { variables: newValue } );
	return (
		<div className={ className }>
			<GraphiQL
				fetcher={ graphQLFetcher }
				query={ query }
				variables={ variables }
				onEditQuery={ onEditQuery }
				onEditVariables={ onEditVariables }
				docExplorerOpen={ false }
				headerEditorEnabled={ false }
			/>
		</div>
	);
}

export default EditBlock;
