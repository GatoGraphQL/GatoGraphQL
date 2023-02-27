/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { Card, CardHeader, CardBody } from '@wordpress/components';
import { MarkdownInfoModalButton } from '../markdown-modal';
import { InfoTooltip } from '../info-tooltip';
import { __ } from '@wordpress/i18n';

/**
 * Display an error message if loading data failed
 */
const withCard = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const {
			header,
			tooltip,
			isSelected,
			getMarkdownContentCallback
		} = props;
		const documentationTitle = __(`Documentation for: "${ header }"`, 'graphql-api');
		return (
			<Card { ...props }>
				<CardHeader isShady>
					<span>
						{ header }
						{ !! tooltip &&
							<InfoTooltip
								{ ...props }
								text={ tooltip }
							/>
						}
						{ !! getMarkdownContentCallback && isSelected && (
							<MarkdownInfoModalButton
								{ ...props }
								title={ documentationTitle }
								getMarkdownContentCallback={ getMarkdownContentCallback }
							/>
						) }
					</span>
				</CardHeader>
				<CardBody>
					<WrappedComponent
						{ ...props }
					/>
				</CardBody>
			</Card>
		);
	},
	'withCard'
);

export default withCard;
