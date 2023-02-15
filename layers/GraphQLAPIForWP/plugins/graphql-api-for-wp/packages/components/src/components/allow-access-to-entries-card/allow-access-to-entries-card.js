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

const AllowAccessToEntriesCard = ( props ) => {
	const {
		isSelected,
		setAttributes,
		attributes: {
			entries,
			behavior,
		},
		entriesAttributeName="entries",
		behaviorAttributeName="behavior",
		entriesHeader,
		entriesLabelDescIntro,
		labelExampleItem,
		labelExampleEntries,
	} = props;
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
	const entriesLabelDescRegex = __('Entries surrounded with "/" or "#" are evaluated as regex (regular expressions).', 'graphql-api');
	const entriesLabelDescExamples = __('For example, "%1$s" is matched by any of the following entries: %2$s.', 'graphql-api')
		.replace('%1$s', labelExampleItem)
		.replace(
			'%2$s',
			`"${ labelExampleEntries.join('", "') }"`
		);
	const entriesLabel = `${ entriesLabelDescIntro } ${ entriesLabelDescRegex } ${ entriesLabelDescExamples }`;
	return (
		<>
			<div>
				<span>
					<em>{ entriesHeader }</em>
				</span>
				<EditableArrayTextareaControl
					{ ...props }
					attributeName={ entriesAttributeName }
					values={ entries }
					help={ entriesLabel }
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
								[ behaviorAttributeName ]: newValue
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
] )( AllowAccessToEntriesCard );