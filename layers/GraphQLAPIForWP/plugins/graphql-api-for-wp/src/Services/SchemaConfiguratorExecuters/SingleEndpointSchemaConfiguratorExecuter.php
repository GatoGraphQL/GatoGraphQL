<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptionValues;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SingleEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers\GraphQLEndpointHandler;

class SingleEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?SingleEndpointSchemaConfigurator $singleEndpointSchemaConfigurator = null;
    private ?GraphQLEndpointHandler $graphQLEndpointHandler = null;

    public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }
    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setSingleEndpointSchemaConfigurator(SingleEndpointSchemaConfigurator $singleEndpointSchemaConfigurator): void
    {
        $this->singleEndpointSchemaConfigurator = $singleEndpointSchemaConfigurator;
    }
    final protected function getSingleEndpointSchemaConfigurator(): SingleEndpointSchemaConfigurator
    {
        /** @var SingleEndpointSchemaConfigurator */
        return $this->singleEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(SingleEndpointSchemaConfigurator::class);
    }
    final public function setGraphQLEndpointHandler(GraphQLEndpointHandler $graphQLEndpointHandler): void
    {
        $this->graphQLEndpointHandler = $graphQLEndpointHandler;
    }
    final protected function getGraphQLEndpointHandler(): GraphQLEndpointHandler
    {
        /** @var GraphQLEndpointHandler */
        return $this->graphQLEndpointHandler ??= $this->instanceManager->getInstance(GraphQLEndpointHandler::class);
    }

    /**
     * This is the Schema Configuration ID
     */
    protected function getCustomPostID(): ?int
    {
        // Only enable it when executing a query against the single endpoint
        if (!$this->getGraphQLEndpointHandler()->isEndpointRequested()) {
            return null;
        }
        return $this->getUserSettingSchemaConfigurationID();
    }

    /**
     * Return the stored Schema Configuration ID
     */
    protected function getUserSettingSchemaConfigurationID(): ?int
    {
        $schemaConfigurationID = $this->getUserSettingsManager()->getSetting(
            SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
            ModuleSettingOptions::VALUE_FOR_SINGLE_ENDPOINT
        );
        // `null` is stored as OPTION_VALUE_NO_VALUE_ID
        if ($schemaConfigurationID === ModuleSettingOptionValues::NO_VALUE_ID) {
            return null;
        }
        return $schemaConfigurationID;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getSingleEndpointSchemaConfigurator();
    }
}
