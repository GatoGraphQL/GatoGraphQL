/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
// import { __ } from '@wordpress/i18n';

/**
 * Application imports
 */
import { CacheControlListEditableOnFocusMultiSelectControl } from '@graphqlapi/components';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<CacheControlListEditableOnFocusMultiSelectControl
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
