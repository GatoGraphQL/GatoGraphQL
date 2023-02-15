/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose } from '@wordpress/compose';
import { RadioControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { withEditableOnFocus } from '../editable-on-focus';
import { withCard } from '../card';
import { InfoTooltip } from '../info-tooltip';
import { withCustomizableConfiguration } from '../customizable-configuration';
import { EditableArrayTextareaControl } from '../editable-array-textarea-control';
import {
	ATTRIBUTE_VALUE_BEHAVIOR_ALLOW,
	ATTRIBUTE_VALUE_BEHAVIOR_DENY,
} from '../behaviors';

const SchemaConfigMetaCard = ( props ) => {
	const {
		isSelected,
		setAttributes,
		attributes: {
			entries,
			behavior,
		},
		behaviorAttributeName="behavior",
		labelEntity,
		labelExampleMetaKey,
		labelExampleEntries,
	} = props;
	const metaKeyDesc = __('List of all the meta keys, to either allow or deny access to, when querying fields `metaValue` and `metaValues` on %s (one entry per line).', 'graphql-api')
		.replace('%s', labelEntity);
	const headsUpDesc = __('Entries surrounded with "/" or "#" are evaluated as regex (regular expressions).', 'graphql-api');
	const examples = labelExampleEntries.join('", "');
	const entryDesc = __('For example, meta key "%1$s" is matched by any of the following entries: %2$s.', 'graphql-api')
		.replace('%1$s', labelExampleMetaKey)
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
								[behaviorAttributeName]: newValue
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
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigMetaCard );