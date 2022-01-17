import { compose, withState } from '@wordpress/compose';
import UserRoles from './user-roles';
import { withAccessControlGroup } from '@graphqlapi/access-control';

/**
 * Same constant as in \PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups::ROLES
 */
const ACCESS_CONTROL_GROUP = 'roles';

export default compose( [
	withState( {
		accessControlGroup: ACCESS_CONTROL_GROUP,
	} ),
	withAccessControlGroup(),
] )( UserRoles );
