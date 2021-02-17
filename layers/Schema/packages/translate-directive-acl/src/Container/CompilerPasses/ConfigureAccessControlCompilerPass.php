<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirectiveACL\Container\CompilerPasses;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PoPSchema\TranslateDirectiveACL\Environment;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;
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
        if (Environment::userMustBeLoggedInToAccessTranslateDirective()) {
            $accessControlManagerDefinition->addMethodCall(
                'addEntriesForDirectives',
                [
                    UserStateAccessControlGroups::STATE,
                    [
                        [AbstractTranslateDirectiveResolver::class, UserStates::IN],
                    ]
                ]
            );
        }
        if ($roles = Environment::anyRoleLoggedInUserMustHaveToAccessTranslateDirective()) {
            $accessControlManagerDefinition->addMethodCall(
                'addEntriesForDirectives',
                [
                    UserRolesAccessControlGroups::ROLES,
                    [
                        [AbstractTranslateDirectiveResolver::class, $roles],
                    ]
                ]
            );
        }
        if ($capabilities = Environment::anyCapabilityLoggedInUserMustHaveToAccessTranslateDirective()) {
            $accessControlManagerDefinition->addMethodCall(
                'addEntriesForDirectives',
                [
                    UserRolesAccessControlGroups::CAPABILITIES,
                    [
                        [AbstractTranslateDirectiveResolver::class, $capabilities],
                    ]
                ]
            );
        }
    }
}
