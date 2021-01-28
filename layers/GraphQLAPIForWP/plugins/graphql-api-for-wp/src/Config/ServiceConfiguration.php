<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Config;

use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient;
use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLWithExplorerClient;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use GraphQLAPI\GraphQLAPI\Blocks\PersistedQueryGraphiQLBlock;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use GraphQLAPI\GraphQLAPI\Blocks\Overrides\PersistedQueryGraphiQLWithExplorerBlock;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
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
        self::configureOverridingBlocks();
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
     * Maybe override blocks
     *
     * @return void
     */
    protected static function configureOverridingBlocks(): void
    {
        // Maybe use GraphiQL with Explorer
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if (
            $moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER) && $userSettingsManager->getSetting(
                ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                ClientFunctionalityModuleResolver::OPTION_USE_IN_ADMIN_PERSISTED_QUERIES
            )
        ) {
            ContainerBuilderUtils::injectValuesIntoService(
                InstanceManagerInterface::class,
                'overrideClass',
                PersistedQueryGraphiQLBlock::class,
                PersistedQueryGraphiQLWithExplorerBlock::class
            );
        }
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
