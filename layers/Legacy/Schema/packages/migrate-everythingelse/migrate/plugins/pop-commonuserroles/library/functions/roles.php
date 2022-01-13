<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeAPIFacade;

const GD_URE_ROLE_INDIVIDUAL = 'individual';
const GD_URE_ROLE_ORGANIZATION = 'organization';

\PoP\Root\App::getHookManager()->addFilter('gdRoles', 'gdUreRolesImpl');
function gdUreRolesImpl($roles)
{
    $roles = array_merge(
        $roles,
        array(
            GD_URE_ROLE_ORGANIZATION,
            GD_URE_ROLE_INDIVIDUAL,
        )
    );
    return $roles;
}

\PoP\Root\App::getHookManager()->addFilter('getUserRoleCombinations', 'getUserRoleCombinationsCommonroles', 100);
function getUserRoleCombinationsCommonroles($user_role_combinations)
{

    // Each user is either an Individual or an Organization,
    // and each Organization may be a Community or not (set through plugins/...)
    $user_role_combinations = array(
        array(
            GD_ROLE_PROFILE,
            GD_URE_ROLE_INDIVIDUAL,
        ),
        array(
            GD_ROLE_PROFILE,
            GD_URE_ROLE_ORGANIZATION,
        ),
    );
    return $user_role_combinations;
}

\PoP\Root\App::getHookManager()->addFilter('gdUreGetuserrole', 'gdUreGetuserroleCommonroles', 10, 2);
function gdUreGetuserroleCommonroles($role, $user_id)
{
    if (gdUreIsOrganization($user_id)) {
        return GD_URE_ROLE_ORGANIZATION;
    } elseif (gdUreIsIndividual($user_id)) {
        return GD_URE_ROLE_INDIVIDUAL;
    }

    return $role;
}

/**
 * Helper Functions
 */

function gdUreIsOrganization($user_id = null)
{
    $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
    return isProfile($user_id) && $userRoleTypeAPI->hasRole($user_id, GD_URE_ROLE_ORGANIZATION);
}

function gdUreIsIndividual($user_id = null)
{
    $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
    return isProfile($user_id) && $userRoleTypeAPI->hasRole($user_id, GD_URE_ROLE_INDIVIDUAL);
}
