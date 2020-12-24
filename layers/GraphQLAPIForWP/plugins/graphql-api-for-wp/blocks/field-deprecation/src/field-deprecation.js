/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { TextControl, Card, CardHeader, CardBody } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { InfoTooltip, getEditableOnFocusComponentClass } from '@graphqlapi/components';

const FieldDeprecation = ( props ) => {
	const {
		className,
		setAttributes,
		isSelected,
		attributes: { deprecationReason },
		disableHeader
	} = props;
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	return (
		<div className={ componentClassName }>
			<Card>
				{ ! disableHeader && (
					<CardHeader isShady>
						<div>
							{ __('Deprecation reason', 'graphql-api') }
							<InfoTooltip
								{ ...props }
								text={ __('Deprecated fields must not be queried anymore. Indicate why/what field to use instead', 'graphql-api') }
							/>
						</div>
					</CardHeader>
				) }
				<CardBody>
					{ isSelected && (
						<TextControl
							// label={ __('Deprecation Reason', 'graphql-api') }
							type="text"
							value={ deprecationReason }
							className={ className+'__reason' }
							onChange={ newValue =>
								setAttributes( {
									deprecationReason: newValue,
								} )
							}
						/>
					) }
					{ !isSelected && (
						<>
							{ !! deprecationReason && (
								<span>{ deprecationReason }</span>
							) }
							{ ! deprecationReason && (
								<em>{ __('(not set)', 'graphql-api') }</em>
							) }
						</>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default FieldDeprecation;
