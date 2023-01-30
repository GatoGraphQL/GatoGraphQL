/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody } from '@wordpress/components';
import { getEditableOnFocusComponentClass } from '../base-styles'
import { InfoTooltip } from '../info-tooltip';
import { MarkdownInfoModalButton } from '../markdown-modal';
import { GraphQLAPISelect } from '../select';

const SelectCard = ( props ) => {
	const {
		label,
		isSelected,
		tooltip,
		getMarkdownContentCallback,
	} = props;
	const componentClassName = 'graphql-api-select-card';
	const componentClass = `${ componentClassName } ${ getEditableOnFocusComponentClass(isSelected) }`;
	const documentationTitle = __(`Documentation for: "${ label }"`, 'graphql-api');
	return (
		<div className={ componentClass }>
			<Card { ...props }>
				<CardHeader isShady>
					<div>
						{ label }
						{ !! tooltip &&
							<InfoTooltip
								{ ...props }
								text={ tooltip }
							/>
						}
						{ !! getMarkdownContentCallback && (
							<MarkdownInfoModalButton
								title={ documentationTitle }
								getMarkdownContentCallback={ getMarkdownContentCallback }
							/>
						) }
					</div>
				</CardHeader>
				<CardBody>
					<GraphQLAPISelect
						{ ...props }
					/>
				</CardBody>
			</Card>
		</div>
	);
}

export default SelectCard;
