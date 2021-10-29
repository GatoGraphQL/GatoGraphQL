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
use Symfony\Contracts\Service\Attribute\Required;

class SingleEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    protected ?UserSettingsManagerInterface $userSettingsManager = null;
    protected ?ModuleRegistryInterface $moduleRegistry = null;
    protected ?SingleEndpointSchemaConfigurator $endpointSchemaConfigurator = null;
    protected ?GraphQLEndpointHandler $graphQLEndpointHandler = null;

    public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= $this->instanceManager->getInstance(UserSettingsManagerInterface::class);
    }
    public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    protected function getModuleRegistry(): ModuleRegistryInterface
    {
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    public function setSingleEndpointSchemaConfigurator(SingleEndpointSchemaConfigurator $endpointSchemaConfigurator): void
    {
        $this->endpointSchemaConfigurator = $endpointSchemaConfigurator;
    }
    protected function getSingleEndpointSchemaConfigurator(): SingleEndpointSchemaConfigurator
    {
        return $this->endpointSchemaConfigurator ??= $this->instanceManager->getInstance(SingleEndpointSchemaConfigurator::class);
    }
    public function setGraphQLEndpointHandler(GraphQLEndpointHandler $graphQLEndpointHandler): void
    {
        $this->graphQLEndpointHandler = $graphQLEndpointHandler;
    }
    protected function getGraphQLEndpointHandler(): GraphQLEndpointHandler
    {
        return $this->graphQLEndpointHandler ??= $this->instanceManager->getInstance(GraphQLEndpointHandler::class);
    }

    //#[Required]
    final public function autowireSingleEndpointSchemaConfiguratorExecuter(
        ModuleRegistryInterface $moduleRegistry,
        SingleEndpointSchemaConfigurator $endpointSchemaConfigurator,
        GraphQLEndpointHandler $graphQLEndpointHandler,
    ): void {
        $this->moduleRegistry = $moduleRegistry;
        $this->endpointSchemaConfigurator = $endpointSchemaConfigurator;
        $this->graphQLEndpointHandler = $graphQLEndpointHandler;
        $this->getUserSettingsManager() = UserSettingsManagerFacade::getInstance();
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
        if ($schemaConfigurationID == ModuleSettingOptionValues::NO_VALUE_ID) {
            return null;
        }
        return $schemaConfigurationID;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getEndpointSchemaConfigurator();
    }
}
