/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withCard } from '../card';
import { GraphQLAPISelect } from '../select';

export default compose( [
	withState( {
		className: 'graphql-api-select-card',
	} ),
	withEditableOnFocus(),
	withCard(),
] )( GraphQLAPISelect );
