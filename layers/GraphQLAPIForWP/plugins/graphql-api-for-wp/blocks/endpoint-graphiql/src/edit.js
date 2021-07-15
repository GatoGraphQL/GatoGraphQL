/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
// import { __ } from '@wordpress/i18n';

/**
 * Application imports
 */
import EndpointGraphiQL from './endpoint-graphiql';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<EndpointGraphiQL
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
