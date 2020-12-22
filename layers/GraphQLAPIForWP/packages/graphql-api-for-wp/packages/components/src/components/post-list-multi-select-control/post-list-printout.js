/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { Card, CardHeader, CardBody, CheckboxControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { EMPTY_LABEL } from '../../default-configuration';
import withSpinner from '../loading/with-spinner';
import withErrorMessage from '../loading/with-error-message';
import '../base-styles/checkbox-list.scss';
import { getLabelForNotFoundElement } from '../helpers';

/**
 * Print the selected Access Control Lists.
 *
 * @param {Object} props
 */
const PostListPrintoutBody = ( props ) => {
	const { items, selectedItems, emptyLabel } = props;
	const emptyLabelString = emptyLabel != undefined ? emptyLabel : EMPTY_LABEL;

	/**
	 * Create a dictionary, with ID as key, and title as the value
	 */
	let itemsDictionary = {};
	items.forEach(function(item) {
		itemsDictionary[ item.id ] = item.title;
	} );
	return (
		<>
			{ !! selectedItems.length && (
				<ul class="checkbox-list">
					{ selectedItems.map( selectedItemID =>
						<li
							key={ selectedItemID }
						>
							<CheckboxControl
								label={ itemsDictionary[selectedItemID] || getLabelForNotFoundElement(selectedItemID) }
								checked={ true }
								disabled={ true }
							/>
						</li>
					) }
				</ul>
			) }
			{ !selectedItems.length && (
				emptyLabelString
			) }
		</>
	);
}

/**
 * Add a spinner when loading the typeFieldNames and typeFields is not empty
 */
const WithSpinnerPostListPrintoutBody = compose( [
	withSpinner(),
	withErrorMessage(),
] )( PostListPrintoutBody );

/**
 * Check if the selectedItems are empty, then do not show the spinner
 * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
 *
 * @param {Object} props
 */
const MaybeWithSpinnerPostListPrintout = ( props ) => {
	const { selectedItems } = props;
	if ( !! selectedItems.length ) {
		return (
			<WithSpinnerPostListPrintoutBody { ...props } />
		)
	}
	return (
		<PostListPrintoutBody { ...props } />
	);
}

/**
 * Print the selected Access Control Lists.
 *
 * @param {Object} props
 */
const PostListPrintoutCard = ( props ) => {
	const { header } = props;
	return (
		<Card { ...props }>
			<CardHeader isShady>{ header }</CardHeader>
			<CardBody>
				<MaybeWithSpinnerPostListPrintout
					{ ...props }
				/>
			</CardBody>
		</Card>
	);
}

export { MaybeWithSpinnerPostListPrintout };
export default PostListPrintoutCard;
