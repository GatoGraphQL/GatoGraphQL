/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * Settings to recorded the block type. Exportable so it can be re-used by other blocks
 */
export const blockTypeSettings = {
	/**
	 * This is the display title for your block, which can be translated with `i18n` functions.
	 * The block inserter will show this name.
	 */
	title: __( 'GraphiQL', 'graphql-api' ),

	/**
	 * This is a short description for your block, can be translated with `i18n` functions.
	 * It will be shown in the Block Tab in the Settings Sidebar.
	 */
	description: __(
		'GraphiQL client to query the GraphQL server.',
		'graphql-api'
	),

	/**
	 * Blocks are grouped into categories to help users browse and discover them.
	 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
	 */
	category: 'graphql-api-persisted-query',

	/**
	 * An icon property should be specified to make it easier to identify a block.
	 * These can be any of WordPress’ Dashicons, or a custom svg element.
	 */
	icon: (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			height="64"
			width="64"
			viewBox="0 0 29.999 30"
			fill="#e10098"
		>
			<path d="M4.08 22.864l-1.1-.636L15.248.98l1.1.636z" />
			<path d="M2.727 20.53h24.538v1.272H2.727z" />
			<path d="M15.486 28.332L3.213 21.246l.636-1.1 12.273 7.086zm10.662-18.47L13.874 2.777l.636-1.1 12.273 7.086z" />
			<path d="M3.852 9.858l-.636-1.1L15.5 1.67l.636 1.1z" />
			<path d="M25.922 22.864l-12.27-21.25 1.1-.636 12.27 21.25zM3.7 7.914h1.272v14.172H3.7zm21.328 0H26.3v14.172h-1.272z" />
			<path d="M15.27 27.793l-.555-.962 10.675-6.163.555.962z" />
			<path d="M27.985 22.5a2.68 2.68 0 0 1-3.654.981 2.68 2.68 0 0 1-.981-3.654 2.68 2.68 0 0 1 3.654-.981c1.287.743 1.724 2.375.98 3.654M6.642 10.174a2.68 2.68 0 0 1-3.654.981A2.68 2.68 0 0 1 2.007 7.5a2.68 2.68 0 0 1 3.654-.981 2.68 2.68 0 0 1 .981 3.654M2.015 22.5a2.68 2.68 0 0 1 .981-3.654 2.68 2.68 0 0 1 3.654.981 2.68 2.68 0 0 1-.981 3.654c-1.287.735-2.92.3-3.654-.98m21.343-12.326a2.68 2.68 0 0 1 .981-3.654 2.68 2.68 0 0 1 3.654.981 2.68 2.68 0 0 1-.981 3.654 2.68 2.68 0 0 1-3.654-.981M15 30a2.674 2.674 0 1 1 2.674-2.673A2.68 2.68 0 0 1 15 30m0-24.652a2.67 2.67 0 0 1-2.674-2.674 2.67 2.67 0 1 1 5.347 0A2.67 2.67 0 0 1 15 5.347" />
		</svg>
	),

	/**
	 * Block default attributes.
	 */
	attributes: {
		/**
		 * If not set as an empty string by default, when first initializing a block the query/variables would be undefined
		 * In that case, initialize the query with an initial value, and do not let GraphiQL get the initial value from the cache
		 * This is because of a potential bug: the state is not saved until executing `onEditQuery` or `onEditVariables`, meaning that the user needs to edit the inputs
		 * However, if the previous input is good (eg: the new query uses the same variables as the last query) and the user never edits it again, the state will not be saved
		 * To force the user to always edit the query, and thus save the state, then initialize the inputs to some default empty value, which is not useful as it is to the query
		 *
		 * Same attribute name as defined in
		 * GraphQLAPI\GraphQLAPI\Blocks\AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY
		 */
		query: {
			type: 'string',
			default: window.graphqlApiGraphiql?.defaultQuery,
		},
		/**
		 * Same attribute name as defined in
		 * GraphQLAPI\GraphQLAPI\Blocks\AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES
		 */
		variables: {
			type: 'string',
			default: '',
		},
		// Make it wide alignment by default
		align: {
			type: 'string',
			default: 'wide',
		},
	},

	/**
	 * Example for the Inspector Help Panel
	 */
	example: {
		attributes: {
			query: 'query {\n  posts(limit:3) {\n    id\n    title\n  }\n}',
		},
	},

	/**
	 * Optional block extended support features.
	 */
	supports: {
		// Alignment options
		align: [ 'center', 'wide', 'full' ],
		// Remove the support for the custom className.
		customClassName: false,
		// Remove support for an HTML mode.
		html: false,
		// Only insert block through a template
		inserter: false,
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by the block editor into `post_content`.
	 *
	 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
	 *
	 * @return {WPElement} Element to render.
	 */
	save() {
		return null;
	},
}
