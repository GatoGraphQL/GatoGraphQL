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
		const {
			header,
		} = props;
		return (
			<Card { ...props }>
				<CardHeader isShady>
					<CardHeaderContent
						{ ...props }
						documentationTitle={ __(`Documentation for: "${ header }"`, 'graphql-api') }
						header={ `🔒 ${ header }` }
					/>
					<GoProLink />
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
