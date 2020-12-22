import { __ } from '@wordpress/i18n';
import { compose } from '@wordpress/compose';
import SchemaFeedback from './schema-feedback';
import { withFieldDirectiveMultiSelectControl } from '@graphqlapi/components';
import '../../../../graphql-api/packages/components/src/components/base-styles/editable-on-focus.scss';

export default compose( [
	withFieldDirectiveMultiSelectControl(),
] )( SchemaFeedback );
