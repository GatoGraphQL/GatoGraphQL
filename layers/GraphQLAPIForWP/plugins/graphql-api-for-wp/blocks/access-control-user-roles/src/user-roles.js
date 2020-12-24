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

const UserRolesSelectCard = ( props ) => {
	const { roles, isSelected, attributes: { value } } = props;
	/**
	 * React Select expects an object with this format:
	 * { value: ..., label: ... },
	 */
	const options = roles.map(item => ( { value: item, label: item } ) )
	/**
	 * React Select expects to pass the same elements from the options as defaultValue,
	 * including the label
	 * { value: ..., label: ... },
	 */
	const defaultValue = value.map(item => ( { value: item, label: item } ) )
	/**
	 * Check if the roles have not been fetched yet, and editing the component (isSelected => true), then show the spinner
	 * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
	 */
	const maybeShowSpinnerOrError = !roles?.length && isSelected;
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

// const WithSpinnerUserRoles = compose( [
// 	withSpinner(),
// 	withErrorMessage(),
// ] )( UserRolesSelectCard );

// /**
//  * Check if the roles have not been fetched yet, and editing the component (isSelected => true), then show the spinner
//  * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerUserRoles = ( props ) => {
// 	const { isSelected, roles } = props;
// 	if ( !roles?.length && isSelected ) {
// 		return (
// 			<WithSpinnerUserRoles { ...props } />
// 		)
// 	}
// 	return (
// 		<UserRolesSelectCard { ...props } />
// 	);
// }

export default compose( [
	withState( {
		label: __('Users with any of these roles', 'graphql-api'),
	} ),
	withSelect( ( select ) => {
		const {
			getRoles,
			hasRetrievedRoles,
			getRetrievingRolesErrorMessage,
		} = select ( 'graphql-api/access-control-user-roles' );
		return {
			roles: getRoles(),
			hasRetrievedItems: hasRetrievedRoles(),
			errorMessage: getRetrievingRolesErrorMessage(),
		};
	} ),
] )( UserRolesSelectCard/*MaybeWithSpinnerUserRoles*/ );
