import { compose } from '@wordpress/compose';
import AccessControl from './access-control';
import { withFieldDirectiveMultiSelectControl } from '@graphqlapi/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import '../../../packages/components/src/components/base-styles/editable-on-focus.scss';

export default compose( [
	withFieldDirectiveMultiSelectControl(),
] )( AccessControl );
