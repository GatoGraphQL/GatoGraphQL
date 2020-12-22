/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * EditBlock and blockTypeSettings are decouled, so that blockTypeSettings can be reused
 * for the GraphiQL with Explorer block without including the unneeded EditBlock code (700kb!)
 */
import { blockTypeSettings } from './block-type-settings.js';
import EditBlock from './edit.js';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType( 'graphql-api/graphiql', {
    /**
     * Shared settings
     */
    ...blockTypeSettings,

    /**
     * Custom settings for this block
     *
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
} );

/**
 * Export it for GraphiQL with Explorer block
 */
export { blockTypeSettings };
