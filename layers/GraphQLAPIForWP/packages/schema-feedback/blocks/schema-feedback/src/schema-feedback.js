/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { TextControl, Card, CardHeader, CardBody, RadioControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { LinkableInfoTooltip, getEditableOnFocusComponentClass } from '@graphqlapi/components';
import {
	ATTRIBUTE_VALUE_FEEDBACK_TYPE_NOTICE,
	ATTRIBUTE_VALUE_FEEDBACK_TYPE_WARNING,
} from './feedback-type-values';

const SchemaFeedback = ( props ) => {
	const {
		className,
		setAttributes,
		isSelected,
		attributes: {
			feedbackType = ATTRIBUTE_VALUE_FEEDBACK_TYPE_NOTICE,
			feedbackMessage
		},
		disableHeader
	} = props;
	const options = [
		{
			label: __('Notice', 'graphql-api-schema-feedback'),
			value: ATTRIBUTE_VALUE_FEEDBACK_TYPE_NOTICE,
		},
		{
			label: __('Warning', 'graphql-api-schema-feedback'),
			value: ATTRIBUTE_VALUE_FEEDBACK_TYPE_WARNING,
		},
	];
	const optionValues = options.map( option => option.value );

	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	const documentationLink = 'https://graphql-api.com/documentation/#cache-control'
	return (
		<div className={ componentClassName }>
			<Card>
				{ ! disableHeader && (
					<CardHeader isShady>
						{ __('Feedback type and message', 'graphql-api-schema-feedback') }
						<LinkableInfoTooltip
							{ ...props }
							text={ __('Provide a message to be added to the response, whenever the selected fields/directives are queried', 'graphql-api-schema-feedback') }
							href={ documentationLink }
						/ >
					</CardHeader>
				) }
				<CardBody>
					{ !isSelected && (
						<>
							{ ( feedbackType == ATTRIBUTE_VALUE_FEEDBACK_TYPE_NOTICE || !optionValues.includes(feedbackType)) &&
								<span>📢 { __('Notice', 'graphql-api-schema-feedback') }</span>
							}
							{ feedbackType == ATTRIBUTE_VALUE_FEEDBACK_TYPE_WARNING &&
								<span>⚠️ { __('Warning', 'graphql-api-schema-feedback') }</span>
							}
						</>
					) }
					{ isSelected &&
						<RadioControl
							{ ...props }
							options={ options }
							selected={ feedbackType }
							onChange={ newValue => (
								setAttributes( {
									feedbackType: newValue
								} )
							)}
						/>
					}
					{ isSelected && (
						<TextControl
							label={ __('Message', 'graphql-api-schema-feedback') }
							type="text"
							value={ feedbackMessage }
							className={ className+'__message' }
							onChange={ newValue =>
								setAttributes( {
									feedbackMessage: newValue,
								} )
							}
						/>
					) }
					{ !isSelected && (
						<>
							{ !! feedbackMessage && (
								<span>{ feedbackMessage }</span>
							) }
							{ ! feedbackMessage && (
								<em>{ __('(not set)', 'graphql-api-schema-feedback') }</em>
							) }
						</>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaFeedback;
