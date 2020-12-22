/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose, withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './store';
import { SelectCard } from '@graphqlapi/components';

const UserCapabilitiesSelectCard = ( props ) => {
	const { capabilities, isSelected, attributes: { value } } = props;
	/**
	 * React Select expects an object with this format:
	 * { value: ..., label: ... },
	 */
	const options = capabilities.map(item => ( { value: item, label: item } ) )
	/**
	 * React Select expects to pass the same elements from the options as defaultValue,
	 * including the label
	 * { value: ..., label: ... },
	 */
	const defaultValue = value.map(item => ( { value: item, label: item } ) )
	/**
	 * Check if the capabilities have not been fetched yet, and editing the component (isSelected => true), then show the spinner
	 * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
	 */
	const maybeShowSpinnerOrError = !capabilities?.length && isSelected;
	return (
		<SelectCard
			{ ...props }
			attributeName="value"
			options={ options }
			defaultValue={ defaultValue }
			maybeShowSpinnerOrError={ maybeShowSpinnerOrError }
		/>
	);
}

// const WithSpinnerUserCapabilities = compose( [
// 	withSpinner(),
// 	withErrorMessage(),
// ] )( UserCapabilitiesSelectCard );

// /**
//  * Check if the capabilities have not been fetched yet, and editing the component (isSelected => true), then show the spinner
//  * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerUserCapabilities = ( props ) => {
// 	const { isSelected, capabilities } = props;
// 	if ( !capabilities?.length && isSelected ) {
// 		return (
// 			<WithSpinnerUserCapabilities { ...props } />
// 		)
// 	}
// 	return (
// 		<UserCapabilitiesSelectCard { ...props } />
// 	);
// }

export default compose( [
	withState( {
		label: __('Users with any of these capabilities', 'graphql-api'),
	} ),
	withSelect( ( select ) => {
		const {
			getCapabilities,
			hasRetrievedCapabilities,
			getRetrievingCapabilitiesErrorMessage,
		} = select ( 'graphql-api/access-control-user-capabilities' );
		return {
			capabilities: getCapabilities(),
			hasRetrievedItems: hasRetrievedCapabilities(),
			errorMessage: getRetrievingCapabilitiesErrorMessage(),
		};
	} ),
] )( UserCapabilitiesSelectCard/*MaybeWithSpinnerUserCapabilities*/ );
