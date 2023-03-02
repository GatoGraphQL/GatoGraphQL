/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { Card, CardHeader, CardBody } from '@wordpress/components';
import { CardHeaderContent } from '../card-header-content';
import { GoProLink } from '../go-pro-link';
import { __ } from '@wordpress/i18n';

const withPROCard = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		return (
			<Card { ...props }>
				<CardHeader isShady>
					<CardHeaderContent
						{ ...props }
					/>
					<GoProLink
						className="button button-secondary"
					/>
				</CardHeader>
				<CardBody>
					<WrappedComponent
						{ ...props }
					/>
				</CardBody>
			</Card>
		);
	},
	'withPROCard'
);

export default withPROCard;
