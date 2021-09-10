<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootTypeResolver;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;

class ConfigureAccessControlCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $accessControlManagerDefinition = $containerBuilderWrapper->getDefinition(AccessControlManagerInterface::class);
        // Obtain the capabilities from another service
        $userAuthorizationDefinitionService = str_replace('\\', '\\\\', UserAuthorizationInterface::class);
        $capabilities = [
            $this->createExpression(sprintf(
                'service("%s").getSchemaEditorAccessCapability()',
                $userAuthorizationDefinitionService
            ))
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
