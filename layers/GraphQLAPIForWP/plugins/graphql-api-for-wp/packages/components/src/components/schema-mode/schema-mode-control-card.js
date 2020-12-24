/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { getEditableOnFocusComponentClass } from '../base-styles'
import { InfoTooltip } from '../info-tooltip';
import SchemaModeControl from './schema-mode-control';

const SchemaModeControlCard = ( props ) => {
	const { isSelected } = props;
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					<div>
						{ __('Public/Private Schema', 'graphql-api') }
						<InfoTooltip
							{ ...props }
							text={ __('Default: use value from Settings. Public: fields/directives are always visible. Private: fields/directives are hidden unless rules are satisfied.', 'graphql-api') }
						/>
					</div>
				</CardHeader>
				<CardBody>
					<SchemaModeControl
						{ ...props }
					/>
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaModeControlCard;
