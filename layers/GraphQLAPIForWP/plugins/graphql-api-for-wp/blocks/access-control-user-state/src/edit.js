import { compose, withState } from '@wordpress/compose';
import UserState from './user-state';
import { withAccessControlGroup } from '@graphqlapi/access-control';

/**
 * Same constant as in \PoPCMSSchema\UserStateAccessControl\Services\AccessControlGroups::STATE
 */
const ACCESS_CONTROL_GROUP = 'state';

export default compose( [
	withState( {
		accessControlGroup: ACCESS_CONTROL_GROUP,
	} ),
	withAccessControlGroup(),
] )( UserState );
