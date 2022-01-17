<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesACL\Container\CompilerPasses;

use PoP\AccessControl\Services\AccessControlGroups as AccessControlGroups;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;
use PoPCMSSchema\UserRolesACL\Environment;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPCMSSchema\UserStateAccessControl\Services\AccessControlGroups as UserStateAccessControlGroups;

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
                        [RootObjectTypeResolver::class, 'roles'],
                        [UserObjectTypeResolver::class, 'roles'],
                        [RootObjectTypeResolver::class, 'capabilities'],
                        [UserObjectTypeResolver::class, 'capabilities'],
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
                        [RootObjectTypeResolver::class, 'roles', UserStates::IN],
                        [UserObjectTypeResolver::class, 'roles', UserStates::IN],
                        [RootObjectTypeResolver::class, 'capabilities', UserStates::IN],
                        [UserObjectTypeResolver::class, 'capabilities', UserStates::IN],
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
                        [RootObjectTypeResolver::class, 'roles', $roles],
                        [UserObjectTypeResolver::class, 'roles', $roles],
                        [RootObjectTypeResolver::class, 'capabilities', $roles],
                        [UserObjectTypeResolver::class, 'capabilities', $roles],
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
                        [RootObjectTypeResolver::class, 'roles', $capabilities],
                        [UserObjectTypeResolver::class, 'roles', $capabilities],
                        [RootObjectTypeResolver::class, 'capabilities', $capabilities],
                        [UserObjectTypeResolver::class, 'capabilities', $capabilities],
                    ]
                ]
            );
        }
    }
}
