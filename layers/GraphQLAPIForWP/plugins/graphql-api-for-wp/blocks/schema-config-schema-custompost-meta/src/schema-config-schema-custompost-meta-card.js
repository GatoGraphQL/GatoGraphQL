/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';
import {
	InfoTooltip,
	withCard,
	withCustomizableConfiguration,
	withEditableOnFocus,
	EditableArrayTextareaControl,
} from '@graphqlapi/components';

const SchemaConfigCustomPostMetaCard = ( props ) => {
	const {
		attributes: {
			entries,
			behavior,
		},
	} = props;
	const helpText = __('List of all the meta keys, to either allow or deny access to, when querying fields `metaValue` and `metaValues` on %s', 'graphql-api')
		.replace('%s', __('custom posts', 'graphql-api'));
	return (
		<>
			<div>
				<span>
					<em>{ __('Meta keys:', 'graphql-api') }</em>
					<InfoTooltip
						{ ...props }
						text={ helpText }
					/>
				</span>
				<EditableArrayTextareaControl
					{ ...props }
					attributeName='entries'
					values={ entries }
					help={ __('<strong>Heads up:</strong> Entries surrounded with ... @TODO complete!!!', 'graphql-api') }
					rows='10'
				/>
			</div>
		</>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Custom Post Meta', 'graphql-api'),
		className: 'graphql-api-custompost-meta',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigCustomPostMetaCard );