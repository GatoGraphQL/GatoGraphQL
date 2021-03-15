<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;
use Symfony\Component\ExpressionLanguage\Expression;

class ConfigureAccessControlCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilder): void
    {
        $accessControlManagerDefinition = $containerBuilder->getDefinition(AccessControlManagerInterface::class);
        // Obtain the capabilities from another service
        $userAuthorizationDefinitionService = str_replace('\\', '\\\\', UserAuthorizationInterface::class);
        $capabilities = [
            new Expression(sprintf(
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
