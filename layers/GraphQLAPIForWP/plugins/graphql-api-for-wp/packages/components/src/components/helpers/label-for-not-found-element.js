/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';

/**
 * Label to print whenever the element is not found among the options
 *
 * @param {int} id
 */
const getLabelForNotFoundElement = ( id ) => sprintf(
	__('(Undefined or unpublished item with ID \'%s\')', 'graphql-api'),
	id
);

export default getLabelForNotFoundElement;
