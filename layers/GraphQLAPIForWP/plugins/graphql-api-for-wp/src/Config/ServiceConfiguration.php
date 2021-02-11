<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Config;

use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient;
use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLWithExplorerClient;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
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
        self::overrideServiceClasses();
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

    /**
     * Override service classes
     */
    protected static function overrideServiceClasses(): void
    {
        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            GraphiQLClient::class,
            \GraphQLAPI\GraphQLAPI\Clients\Overrides\GraphiQLClient::class
        );
        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            GraphiQLWithExplorerClient::class,
            \GraphQLAPI\GraphQLAPI\Clients\Overrides\GraphiQLWithExplorerClient::class
        );
    }
}
