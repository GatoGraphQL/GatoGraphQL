/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
// import { __ } from '@wordpress/i18n';

/**
 * Application imports
 */
import PersistedQueryAPIHierarchy from './persisted-query-api-hierarchy';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<PersistedQueryAPIHierarchy
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
