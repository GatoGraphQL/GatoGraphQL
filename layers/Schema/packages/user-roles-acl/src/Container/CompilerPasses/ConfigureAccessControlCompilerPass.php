<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesACL\Container\CompilerPasses;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PoPSchema\UserRolesACL\Environment;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoP\AccessControl\Services\AccessControlGroups as AccessControlGroups;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups as UserStateAccessControlGroups;

class ConfigureAccessControlCompilerPass implements CompilerPassInterface
{
    /**
     * GraphQL persisted query for Introspection query
     */
    public function process(ContainerBuilder $containerBuilder): void
    {
        $accessControlManagerDefinition = $containerBuilder->getDefinition(AccessControlManagerInterface::class);
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
