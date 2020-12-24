import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';
import FieldDeprecation from './field-deprecation';
import { withFieldDirectiveMultiSelectControl } from '@graphqlapi/components';
import '../../../packages/components/src/components/base-styles/editable-on-focus.scss';

export default compose( [
	withState( {
		disableDirectives: true,
		// disableHeader: true,
		fieldHeader: __('Fields to deprecate', 'graphql-api'),
	} ),
	withFieldDirectiveMultiSelectControl(),
] )( FieldDeprecation );
