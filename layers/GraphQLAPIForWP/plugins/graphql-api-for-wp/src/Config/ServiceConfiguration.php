<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Config;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use PoP\Root\Container\ContainerBuilderUtils;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    /**
     * Validate that only the right users can access private fields
     *
     * @return void
     */
    protected static function configure()
    {
        self::configureAccessControl();
    }

    /**
     * Validate that only the right users can access private fields
     *
     * @return void
     */
    protected static function configureAccessControl(): void
    {
        $schemaEditorAccessCapability = UserAuthorization::getSchemaEditorAccessCapability();
        $capabilities = [$schemaEditorAccessCapability];
        ContainerBuilderUtils::injectValuesIntoService(
            AccessControlManagerInterface::class,
            'addEntriesForFields',
            UserRolesAccessControlGroups::CAPABILITIES,
            [
                [RootTypeResolver::class, 'accessControlLists', $capabilities],
                [RootTypeResolver::class, 'cacheControlLists', $capabilities],
                [RootTypeResolver::class, 'fieldDeprecationLists', $capabilities],
            ]
        );
    }
}
