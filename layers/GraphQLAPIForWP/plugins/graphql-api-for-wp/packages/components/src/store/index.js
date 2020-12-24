/**
 * WordPress dependencies
 */
import { registerStore } from '@wordpress/data';

/**
 * Internal dependencies
 */
import reducer from './reducer';
import * as selectors from './selectors';
import * as actions from './action-creators';
import resolvers from './resolvers';
import controls from './controls';

/**
 * Module Constants
 */
const MODULE_KEY = 'graphql-api/components';

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

const store = registerStore( MODULE_KEY, storeConfig );

export default store;
