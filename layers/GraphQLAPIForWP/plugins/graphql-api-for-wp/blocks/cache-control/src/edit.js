import { compose } from '@wordpress/compose';
import CacheControl from './cache-control';
import { withFieldDirectiveMultiSelectControl } from '@graphqlapi/components';
import '../../../packages/components/src/components/base-styles/editable-on-focus.scss';

export default compose( [
	withFieldDirectiveMultiSelectControl(),
] )( CacheControl );
