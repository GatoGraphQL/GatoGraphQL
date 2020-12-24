import { compose, withState } from '@wordpress/compose';
import DisableAccess from './disable-access';
import { withAccessControlGroup } from '@graphqlapi/access-control';

/**
 * Same constant as in \PoP\AccessControl\Services\AccessControlGroups::DISABLED
 */
const ACCESS_CONTROL_GROUP = 'disabled';

export default compose( [
	withState( {
		accessControlGroup: ACCESS_CONTROL_GROUP,
	} ),
	withAccessControlGroup(),
] )( DisableAccess );
