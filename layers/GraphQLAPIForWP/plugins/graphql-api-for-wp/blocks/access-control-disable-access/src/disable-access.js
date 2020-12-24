import { __ } from '@wordpress/i18n';
import { Card, CardBody } from '@wordpress/components';

const DisableAccess = ( props ) => {
	const { className } = props;
	return (
		<div className={ className+'__disable_access' }>
			<Card { ...props }>
				<CardBody>
					<span>⛔️ { __('Nobody', 'graphql-api') }</span>
				</CardBody>
			</Card>
		</div>
	);
}

export default DisableAccess;
