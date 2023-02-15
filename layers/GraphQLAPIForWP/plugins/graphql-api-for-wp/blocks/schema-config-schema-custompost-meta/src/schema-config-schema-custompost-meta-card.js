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
	const metaKeyDesc = __('List of all the meta keys, to either allow or deny access to, when querying fields `metaValue` and `metaValues` on %s (one entry per line).', 'graphql-api')
		.replace('%s', __('custom posts', 'graphql-api'));
	const headsUpDesc = __('Entries surrounded with "/" or "#" are evaluated as regex (regular expressions).', 'graphql-api');
	const examples = [
		'_edit_last',
		'/_edit_.*/',
		'#_edit_([a-zA-Z]*)#',
	].join('", "');
	const entryDesc = __('For example, any of these entries match meta key "%1$s": %2$s.', 'graphql-api')
		.replace('%1$s', '_edit_last')
		.replace(
			'%2$s',
			`"${ examples }"`
		);
	const helpText = `${ metaKeyDesc } ${ headsUpDesc } ${ entryDesc }`;
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
					{/* <InfoTooltip
						{ ...props }
						text={ helpText }
					/> */}
				</span>
				<EditableArrayTextareaControl
					{ ...props }
					attributeName='entries'
					values={ entries }
					help={ helpText }
					rows='10'
				/>
			</div>
			<div>
				<em>{ __('Behavior:', 'graphql-api') }</em>
				<InfoTooltip
					{ ...props }
					text={ __('If "Allow access" is selected, only the configured entries can be accessed, and no other can; with "Deny access", the reverse applies', 'graphql-api') }
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