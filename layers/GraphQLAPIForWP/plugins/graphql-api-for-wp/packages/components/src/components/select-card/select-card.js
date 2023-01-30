/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';
import { withEditableOnFocus } from '../editable-on-focus';
import { withCard } from '../card';
import { EditableSelect } from '../editable-select';

export default compose( [
	withState( {
		className: 'graphql-api-select-card',
	} ),
	withEditableOnFocus(),
	withCard(),
] )( EditableSelect );
