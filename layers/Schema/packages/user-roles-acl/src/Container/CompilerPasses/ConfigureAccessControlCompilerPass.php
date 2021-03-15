<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesACL\Container\CompilerPasses;

use PoP\AccessControl\Services\AccessControlGroups as AccessControlGroups;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;
use PoPSchema\UserRolesACL\Environment;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups as UserStateAccessControlGroups;

class ConfigureAccessControlCompilerPass extends AbstractCompilerPass
{
    /**
     * GraphQL persisted query for Introspection query
     */
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $accessControlManagerDefinition = $containerBuilderWrapper->getDefinition(AccessControlManagerInterface::class);
        // Inject the access control entries
        if (Environment::disableRolesFields()) {
            $accessControlManagerDefinition->addMethodCall(
                'addEntriesForFields',
                [
                    AccessControlGroups::DISABLED,
                    [
                        [RootTypeResolver::class, 'roles'],
                        [UserTypeResolver::class, 'roles'],
                        [RootTypeResolver::class, 'capabilities'],
                        [UserTypeResolver::class, 'capabilities'],
                    ]
                ]
            );
        }
        if (Environment::userMustBeLoggedInToAccessRolesFields()) {
            $accessControlManagerDefinition->addMethodCall(
                'addEntriesForFields',
                [
                    UserStateAccessControlGroups::STATE,
                    [
                        [RootTypeResolver::class, 'roles', UserStates::IN],
                        [UserTypeResolver::class, 'roles', UserStates::IN],
                        [RootTypeResolver::class, 'capabilities', UserStates::IN],
                        [UserTypeResolver::class, 'capabilities', UserStates::IN],
                    ]
                ]
            );
        }
        if ($roles = Environment::anyRoleLoggedInUserMustHaveToAccessRolesFields()) {
            $accessControlManagerDefinition->addMethodCall(
                'addEntriesForFields',
                [
                    UserRolesAccessControlGroups::ROLES,
                    [
                        [RootTypeResolver::class, 'roles', $roles],
                        [UserTypeResolver::class, 'roles', $roles],
                        [RootTypeResolver::class, 'capabilities', $roles],
                        [UserTypeResolver::class, 'capabilities', $roles],
                    ]
                ]
            );
        }
        if ($capabilities = Environment::anyCapabilityLoggedInUserMustHaveToAccessRolesFields()) {
            $accessControlManagerDefinition->addMethodCall(
                'addEntriesForFields',
                [
                    UserRolesAccessControlGroups::CAPABILITIES,
                    [
                        [RootTypeResolver::class, 'roles', $capabilities],
                        [UserTypeResolver::class, 'roles', $capabilities],
                        [RootTypeResolver::class, 'capabilities', $capabilities],
                        [UserTypeResolver::class, 'capabilities', $capabilities],
                    ]
                ]
            );
        }
    }
}
