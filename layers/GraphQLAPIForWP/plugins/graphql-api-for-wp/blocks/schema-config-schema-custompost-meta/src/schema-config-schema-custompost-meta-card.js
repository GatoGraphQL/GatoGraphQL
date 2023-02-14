/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';
import { RadioControl } from '@wordpress/components';

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
import {
	ATTRIBUTE_VALUE_BEHAVIOR_ALLOW,
	ATTRIBUTE_VALUE_BEHAVIOR_DENY,
} from './behavior-meta-values';

const SchemaConfigCustomPostMetaCard = ( props ) => {
	const {
		isSelected,
		setAttributes,
		attributes: {
			entries,
			behavior,
		},
	} = props;
	const helpText = __('List of all the meta keys, to either allow or deny access to, when querying fields `metaValue` and `metaValues` on %s', 'graphql-api')
		.replace('%s', __('custom posts', 'graphql-api'));
	const options = [
		{
			label: __('Allow access', 'graphql-api'),
			value: ATTRIBUTE_VALUE_BEHAVIOR_ALLOW,
		},
		{
			label: __('Deny access', 'graphql-api'),
			value: ATTRIBUTE_VALUE_BEHAVIOR_DENY,
		},
	];
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
			<div>
				<em>{ __('Behavior:', 'graphql-api') }</em>
				<InfoTooltip
					{ ...props }
					text={ __('Are the entries being allowed or denied?', 'graphql-api') }
				/>
				{ !isSelected && (
					<>
						<br />
						{ behavior == ATTRIBUTE_VALUE_BEHAVIOR_ALLOW &&
							<span>✅ { __('Allow access', 'graphql-api') }</span>
						}
						{ behavior == ATTRIBUTE_VALUE_BEHAVIOR_DENY &&
							<span>❌ { __('Deny access', 'graphql-api') }</span>
						}
					</>
				) }
				{ isSelected &&
					<RadioControl
						{ ...props }
						options={ options }
						selected={ behavior }
						onChange={ newValue => (
							setAttributes( {
								behavior: newValue
							} )
						)}
					/>
				}
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