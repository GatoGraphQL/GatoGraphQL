<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use PoP\AccessControl\Services\AccessControlManagerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;

class ConfigureAccessControlCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $accessControlManagerDefinition = $containerBuilder->getDefinition(AccessControlManagerInterface::class);
        // Obtain the capabilities from another service
        $userAuthorizationDefinitionService = str_replace('\\', '\\\\', UserAuthorizationInterface::class);
        $capabilities = [
            sprintf(
                '@=service("%s").getSchemaEditorAccessCapability()',
                $userAuthorizationDefinitionService
            )
        ];
        $accessControlManagerDefinition->addMethodCall(
            'addEntriesForFields',
            [
                UserRolesAccessControlGroups::CAPABILITIES,
                [
                    [RootTypeResolver::class, 'accessControlLists', $capabilities],
                    [RootTypeResolver::class, 'cacheControlLists', $capabilities],
                    [RootTypeResolver::class, 'fieldDeprecationLists', $capabilities],
                ]
            ]
        );
    }
}
