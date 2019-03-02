<?php

/**
 * Functions to ask if it's a specific type of user
 */
function hasRole($role, $user_or_user_id)
{
    if (is_object($user_or_user_id)) {
        $user = $user_or_user_id;
        $user_id = $user->ID;
    } else {
        $user_id = $user_or_user_id;
    }
    
    $roles = getUserRoles($user_id);
    return (in_array($role, $roles));
}

/**
 * Roles
 */

function getUserRoles($user_id)
{
    $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    $user = $cmsapi->getUserBy('id', $user_id);
    $user_roles = $user->roles;
    
    return $user_roles;
};

function getTheUserRole($user_id)
{
    $roles = getUserRoles($user_id);

    // Allow URE to override this function
    return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('getTheUserRole', $roles[0], $user_id);
}
