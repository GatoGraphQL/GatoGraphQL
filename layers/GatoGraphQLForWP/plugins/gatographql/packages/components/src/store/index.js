/**
 * WordPress dependencies
 */
import { select, createReduxStore, register } from '@wordpress/data';

/**
 * Internal dependencies
 */
import reducer from './reducer';
import * as selectors from './selectors';
import * as actions from './actions';
import * as resolvers from './resolvers';

/**
 * Module Constants
 */
const STORE_NAME = 'gatographql/components';

/**
 * Block editor data store configuration.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#registerStore
 *
 * @type {Object}
 */
export const storeConfig = {
	reducer,
	selectors,
	actions,
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
const existingStore = select( STORE_NAME );

/**
 * Store definition for the block directory namespace.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 *
 * @type {Object}
 */
export const store = existingStore || createReduxStore( STORE_NAME, storeConfig );

if ( ! existingStore ) { 
	register( store );
}
