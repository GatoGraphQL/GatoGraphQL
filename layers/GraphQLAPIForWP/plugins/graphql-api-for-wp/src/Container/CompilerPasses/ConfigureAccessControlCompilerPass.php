<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use PoP\AccessControl\Services\AccessControlManagerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;

class ConfigureAccessControlCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $accessControlManagerDefinition = $containerBuilder->getDefinition(AccessControlManagerInterface::class);
        $schemaEditorAccessCapability = UserAuthorization::getSchemaEditorAccessCapability();
        $capabilities = [$schemaEditorAccessCapability];
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
