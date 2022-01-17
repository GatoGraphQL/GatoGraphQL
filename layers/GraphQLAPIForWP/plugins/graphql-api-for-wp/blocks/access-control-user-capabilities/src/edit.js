import { compose, withState } from '@wordpress/compose';
import UserCapabilities from './user-capabilities';
import { withAccessControlGroup } from '@graphqlapi/access-control';

/**
 * Same constant as in \PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups::CAPABILITIES
 */
const ACCESS_CONTROL_GROUP = 'capabilities';

export default compose( [
	withState( {
		accessControlGroup: ACCESS_CONTROL_GROUP,
	} ),
	withAccessControlGroup(),
] )( UserCapabilities );
