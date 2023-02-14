/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';
import { TextareaControl } from '@wordpress/components';
// import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';
import {
	InfoTooltip,
	withCard,
	withCustomizableConfiguration,
	withEditableOnFocus,
} from '@graphqlapi/components';

const SchemaConfigCustomPostMetaCard = ( props ) => {
	const {
		setAttributes,
		attributes: {
			entries,
			behavior,
		},
	} = props;
	// const [ /*text, */setText ] = useState( '' );
	const helpText = __('List of all the meta keys, to either allow or deny access to, when querying fields `metaValue` and `metaValues` on %s', 'graphql-api')
		.replace('%s', __('custom posts', 'graphql-api'));
	const attributeName = 'entries';
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
				<TextareaControl
					// label={ __('Meta keys', 'graphql-api') }
					help={ __('<strong>Heads up:</strong> Entries surrounded with ... @TODO complete!!!', 'graphql-api') }
					// value={ text }
					value={ entries.join('\n') }
					// onChange={ ( value ) => setText( value ) }
					rows='10'
					onChange={ value =>
						setAttributes( {
							[ attributeName ]: value.split('\n')
						} )
					}
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