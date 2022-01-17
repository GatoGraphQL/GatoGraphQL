<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\Helpers;

use PoPCMSSchema\UserRoles\Facades\UserRoleTypeAPIFacade;
use PoPCMSSchema\UserState\Facades\UserStateTypeAPIFacade;

class UserRoleHelper
{
    public static function doesCurrentUserHaveRole(string $roleName): bool
    {
        // Check if the user has the required role
        $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
        $userStateTypeAPI = UserStateTypeAPIFacade::getInstance();
        $userID = $userStateTypeAPI->getCurrentUserID();
        $userRoles = $userRoleTypeAPI->getUserRoles($userID);
        return in_array($roleName, $userRoles);
    }

    public static function doesCurrentUserHaveAnyRole(array $roleNames): bool
    {
        // Check if the user has the required role
        $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
        $userStateTypeAPI = UserStateTypeAPIFacade::getInstance();
        $userID = $userStateTypeAPI->getCurrentUserID();
        $userRoles = $userRoleTypeAPI->getUserRoles($userID);
        return !empty(array_intersect($roleNames, $userRoles));
    }

    public static function doesCurrentUserHaveCapability(string $capability): bool
    {
        // Check if the user has the required role
        $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
        $userStateTypeAPI = UserStateTypeAPIFacade::getInstance();
        $userID = $userStateTypeAPI->getCurrentUserID();
        $userCapabilities = $userRoleTypeAPI->getUserCapabilities($userID);
        return in_array($capability, $userCapabilities);
    }

    public static function doesCurrentUserHaveAnyCapability(array $capabilities): bool
    {
        // Check if the user has the required role
        $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
        $userStateTypeAPI = UserStateTypeAPIFacade::getInstance();
        $userID = $userStateTypeAPI->getCurrentUserID();
        $userCapabilities = $userRoleTypeAPI->getUserCapabilities($userID);
        return !empty(array_intersect($capabilities, $userCapabilities));
    }
}
