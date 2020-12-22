<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesACL\Config;

use PoPSchema\UserRolesACL\Environment;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoP\AccessControl\Services\AccessControlGroups as AccessControlGroups;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups as UserStateAccessControlGroups;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Inject the access control entries
        if (Environment::disableRolesFields()) {
            ContainerBuilderUtils::injectValuesIntoService(
                AccessControlManagerInterface::class,
                'addEntriesForFields',
                AccessControlGroups::DISABLED,
                [
                    [RootTypeResolver::class, 'roles'],
                    [UserTypeResolver::class, 'roles'],
                    [RootTypeResolver::class, 'capabilities'],
                    [UserTypeResolver::class, 'capabilities'],
                ]
            );
        }
        if (Environment::userMustBeLoggedInToAccessRolesFields()) {
            ContainerBuilderUtils::injectValuesIntoService(
                AccessControlManagerInterface::class,
                'addEntriesForFields',
                UserStateAccessControlGroups::STATE,
                [
                    [RootTypeResolver::class, 'roles', UserStates::IN],
                    [UserTypeResolver::class, 'roles', UserStates::IN],
                    [RootTypeResolver::class, 'capabilities', UserStates::IN],
                    [UserTypeResolver::class, 'capabilities', UserStates::IN],
                ]
            );
        }
        if ($roles = Environment::anyRoleLoggedInUserMustHaveToAccessRolesFields()) {
            ContainerBuilderUtils::injectValuesIntoService(
                AccessControlManagerInterface::class,
                'addEntriesForFields',
                UserRolesAccessControlGroups::ROLES,
                [
                    [RootTypeResolver::class, 'roles', $roles],
                    [UserTypeResolver::class, 'roles', $roles],
                    [RootTypeResolver::class, 'capabilities', $roles],
                    [UserTypeResolver::class, 'capabilities', $roles],
                ]
            );
        }
        if ($capabilities = Environment::anyCapabilityLoggedInUserMustHaveToAccessRolesFields()) {
            ContainerBuilderUtils::injectValuesIntoService(
                AccessControlManagerInterface::class,
                'addEntriesForFields',
                UserRolesAccessControlGroups::CAPABILITIES,
                [
                    [RootTypeResolver::class, 'roles', $capabilities],
                    [UserTypeResolver::class, 'roles', $capabilities],
                    [RootTypeResolver::class, 'capabilities', $capabilities],
                    [UserTypeResolver::class, 'capabilities', $capabilities],
                ]
            );
        }
    }
}
