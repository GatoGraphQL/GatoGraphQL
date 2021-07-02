<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\CustomPostMutations\LooseContracts\LooseContractSet;
use PoPSchema\Users\TypeDataLoaders\UserTypeDataLoader;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;

define('GD_ROLE_PROFILE', 'profile');

// Set the default role for the user typeDataLoader
HooksAPIFacade::getInstance()->addFilter('UserTypeDataLoader:query', 'gdUreMaybeProfileRole');
function gdUreMaybeProfileRole($query)
{

    // Only if no $role set yet
    if (!isset($query['role'])) {
        $query['role'] = GD_ROLE_PROFILE;
    }

    return $query;
}

HooksAPIFacade::getInstance()->addFilter('gdRoles', 'gdUreAddProfileRole');
function gdUreAddProfileRole($roles)
{
    $roles[] = GD_ROLE_PROFILE;
    return $roles;
}

// Priority 0: before anything else
HooksAPIFacade::getInstance()->addFilter('getUserRoleCombinations', 'getUserRoleCombinationsProfileRole', 0);
function getUserRoleCombinationsProfileRole($user_role_combinations)
{
    $user_role_combinations = array(
        array(
            GD_ROLE_PROFILE,
        ),
    );
    return $user_role_combinations;
}

function isProfile($user = null)
{
    $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
    return $userRoleTypeDataResolver->hasRole($user, GD_ROLE_PROFILE);
}

/**
 * Determine the threshold capability to split subscriber users from admins / individual / organization users
 */
function userHasProfileAccess($user_id = null)
{
    $editCustomPostsCapability = NameResolverFacade::getInstance()->getName(LooseContractSet::NAME_EDIT_CUSTOMPOSTS_CAPABILITY);
    return userHasAccess($editCustomPostsCapability, $user_id);
}

/**
 * User Type Data Resolver: allow to select users only with at least role GD_ROLE_PROFILE
 */
HooksAPIFacade::getInstance()->addFilter(UserTypeDataLoader::class.':gd_dataload_query', 'gdUreUserlistQuery');
function gdUreUserlistQuery($query)
{
    // The role can only be Profile (Organization + Individual), force there's no other (protection against hackers).
    $roles = gdRoles();
    if (!in_array($query['role'], $roles)) {
        $query['role'] = GD_ROLE_PROFILE;
    }

    return $query;
}
