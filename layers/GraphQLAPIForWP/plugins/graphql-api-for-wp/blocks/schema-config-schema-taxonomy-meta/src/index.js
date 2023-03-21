/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * Application imports
 */
import EditBlock from './edit';

const defaultBehavior = window.graphqlApiSchemaConfigSchemaTaxonomyMeta.defaultBehavior;

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType( 'graphql-api/schema-config-schema-taxonomy-meta', {
	/**
	 * This is the display title for your block, which can be translated with `i18n` functions.
	 * The block inserter will show this name.
	 */
	title: __( 'Taxonomy Meta for the Schema Configuration', 'graphql-api' ),

	/**
	 * This is a short description for your block, can be translated with `i18n` functions.
	 * It will be shown in the Block Tab in the Settings Sidebar.
	 */
	description: __(
		'Configure taxonomy meta options in the Schema Configuration',
		'graphql-api'
	),

	/**
	 * Blocks are grouped into categories to help users browse and discover them.
	 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
	 */
	category: 'graphql-api-schema-config',

	/**
	 * An icon property should be specified to make it easier to identify a block.
	 * These can be any of WordPress’ Dashicons, or a custom svg element.
	 */
	icon: 'admin-users',

	/**
	 * Block default attributes.
	 */
	attributes: {
		/**
		 * Same attribute name as defined in
		 * GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractSchemaConfigCustomizableConfigurationBlock::ATTRIBUTE_NAME_CUSTOMIZE_CONFIGURATION
		 */
		customizeConfiguration: {
			type: 'boolean',
			default: false,
		},
		/**
		 * Same attribute name as defined in
		 * GraphQLAPI\GraphQLAPI\Services\Constants\BlockAttributeNames::ENTRIES
		 */
		entries: {
			type: 'array',
			default: [],
		},
		/**
		 * Same attribute name as defined in
		 * GraphQLAPI\GraphQLAPI\Services\Constants\BlockAttributeNames::BEHAVIOR
		 */
		behavior: {
			type: 'string',
			default: defaultBehavior,
		},
	},

	/**
	 * Optional block extended support features.
	 */
	supports: {
		// Remove the support for the custom className.
		customClassName: false,
		// Remove support for an HTML mode.
		html: false,
		// Insert programatically only
		// inserter: false,
		// Use the block just once per post
		multiple: false,
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
	 *
	 * @param {Object} [props] Properties passed from the editor.
	 *
	 * @return {WPElement} Element to render.
	 */
	edit: EditBlock,

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
} );
