/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { Card, CardHeader, CardBody } from '@wordpress/components';
import { CardHeaderContent } from '../card-header-content';
import { __ } from '@wordpress/i18n';

const withCard = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		return (
			<Card { ...props }>
				<CardHeader isShady>
					<CardHeaderContent
						{ ...props }
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
	'withCard'
);

export default withCard;
