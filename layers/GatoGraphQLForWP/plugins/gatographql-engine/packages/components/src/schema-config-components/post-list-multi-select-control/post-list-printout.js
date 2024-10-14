/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { Card, CardHeader, CardBody, CheckboxControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import withSpinner from '../../components/loading/with-spinner';
import withErrorMessage from '../../components/loading/with-error-message';
import getLabelForNotFoundElement from '../../components/helpers/label-for-not-found-element';
import { NO_ITEMS_SELECTED_LABEL } from '../../default-configuration';
import '../base-styles/checkbox-list.scss';

const noItemsSelectedLabel = NO_ITEMS_SELECTED_LABEL;

/**
 * Print the selected Access Control Lists.
 *
 * @param {Object} props
 */
const PostListPrintoutBody = ( props ) => {
	const { items, selectedItems } = props;

	/**
	 * Create a dictionary, with ID as key (stored under `value`), and title as the value
	 */
	let itemsDictionary = {};
	items.forEach(function(item) {
		itemsDictionary[ item.value ] = item.title;
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
				<em>
					{ noItemsSelectedLabel }
				</em>
			) }
		</>
	);
}

/**
 * Add a spinner when loading the post titles and the post list is not empty
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
