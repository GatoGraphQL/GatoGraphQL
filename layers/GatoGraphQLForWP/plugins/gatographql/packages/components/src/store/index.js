/**
 * WordPress dependencies
 */
import { select, registerStore } from '@wordpress/data';

/**
 * Internal dependencies
 */
import reducer from './reducer';
import * as selectors from './selectors';
import * as actions from './actions';
import resolvers from './resolvers';
import controls from './controls';

/**
 * Module Constants
 */
const MODULE_KEY = 'gatographql/components';

/**
 * Block editor data store configuration.
 *
 * @see https://github.com/WordPress/gutenberg/blob/master/packages/data/README.md#registerStore
 *
 * @type {Object}
 */
export const storeConfig = {
	reducer,
	selectors,
	actions,
	controls,
	resolvers,
};

/**
 * Because this store is in a component, this logic will be called from each
 * of the blocks containing this component, and so the store could be registered
 * multiple times and show an error in the console:
 *
 * > Store "gatographql/components" is already registered. data.min.js:2:19277
 *
 * To avoid this, first check if the store already exists. If not, only then
 * register it.
 */
const store = select( MODULE_KEY ) || registerStore( MODULE_KEY, storeConfig );

export default store;
