<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesACL;

class Environment
{
    public static function disableRolesFields(): bool
    {
        return getenv('DISABLE_ROLES_FIELDS') !== false ? strtolower(getenv('DISABLE_ROLES_FIELDS')) == "true" : false;
    }

    public static function userMustBeLoggedInToAccessRolesFields(): bool
    {
        return getenv('USER_MUST_BE_LOGGED_IN_TO_ACCESS_ROLES_FIELDS') !== false ? strtolower(getenv('USER_MUST_BE_LOGGED_IN_TO_ACCESS_ROLES_FIELDS')) == "true" : false;
    }

    public static function anyRoleLoggedInUserMustHaveToAccessRolesFields(): array
    {
        return getenv('ANY_ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_ROLES_FIELDS') !== false ? json_decode(getenv('ANY_ROLE_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_ROLES_FIELDS')) : [];
    }

    public static function anyCapabilityLoggedInUserMustHaveToAccessRolesFields(): array
    {
        return getenv('ANY_CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_ROLES_FIELDS') !== false ? json_decode(getenv('ANY_CAPABILITY_LOGGED_IN_USER_MUST_HAVE_TO_ACCESS_ROLES_FIELDS')) : [];
    }
}
