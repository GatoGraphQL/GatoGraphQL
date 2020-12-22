import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { getBlockTypes } from '@wordpress/blocks';
import {
	ACCESS_CONTROL_BLOCK_NAME,
	ACCESS_CONTROL_BLOCK_CATEGORY,
} from './block-settings.js';
import { SchemaMode } from '@graphqlapi/components';

const AccessControl = ( props ) => {
	const { className, isIndividualControlForSchemaModeEnabled } = props;
	/**
	 * Only allow blocks under the "Access Control" category, except for this self block
	 */
	const allowedBlocks = getBlockTypes().filter(
		blockType => blockType.category == ACCESS_CONTROL_BLOCK_CATEGORY && blockType.name != ACCESS_CONTROL_BLOCK_NAME
	).map(blockType => blockType.name)
	/**
	 * Add component SchemaMode only if option "individual schema mode" is enabled
	 */
	return (
		<>
			{ isIndividualControlForSchemaModeEnabled &&
				<div className={ className+'__schema_mode' }>
					<SchemaMode
						{ ...props }
						attributeName="schemaMode"
						defaultLabel={ __('As defined in the Schema Configuration', 'graphql-api') }
					/>
				</div>
			}
			<div className={ className+'__who' }>
				<InnerBlocks
					allowedBlocks={ allowedBlocks }
				/>
			</div>
		</>
	);
}

export default AccessControl;
