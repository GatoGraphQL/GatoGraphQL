<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirectiveACL\Container\CompilerPasses;

use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoPSchema\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;
use PoPSchema\TranslateDirectiveACL\Environment;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups as UserStateAccessControlGroups;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigureAccessControlCompilerPass extends AbstractCompilerPass
{
    /**
     * GraphQL persisted query for Introspection query
     */
    protected function doProcess(ContainerBuilder $containerBuilder): void
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
