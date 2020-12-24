/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { Card, CardHeader, CardBody } from '@wordpress/components';

/**
 * Display an error message if loading data failed
 */
const withCard = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const { header } = props;
		return (
			<Card { ...props }>
				<CardHeader isShady>{ header }</CardHeader>
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
