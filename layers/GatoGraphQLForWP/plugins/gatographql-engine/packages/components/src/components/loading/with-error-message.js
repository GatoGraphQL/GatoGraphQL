/**
 * WordPress imports
 */
import { Notice } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';

/**
 * Display an error message if loading data failed
 */
const withErrorMessage = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const { isRequestingItems, errorMessage } = props;
		if ( ! isRequestingItems && errorMessage ) {
			return <div className="multi-select-control__error_message">
				<Notice status="error" isDismissible={ false }>
					{ errorMessage }
				</Notice>
			</div>
		}

		return (
			<WrappedComponent
				{ ...props }
			/>
		);
	},
	'withErrorMessage'
);

export default withErrorMessage;
