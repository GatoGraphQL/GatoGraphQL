<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\AppHelpers;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointBlockHelpers;
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
    private ?EndpointBlockHelpers $endpointBlockHelpers = null;

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
    final public function setEndpointBlockHelpers(EndpointBlockHelpers $endpointBlockHelpers): void
    {
        $this->endpointBlockHelpers = $endpointBlockHelpers;
    }
    final protected function getEndpointBlockHelpers(): EndpointBlockHelpers
    {
        /** @var EndpointBlockHelpers */
        return $this->endpointBlockHelpers ??= $this->instanceManager->getInstance(EndpointBlockHelpers::class);
    }

    /**
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        if (!AppHelpers::isMainAppThread()) {
            return false;
        }
        return parent::isServiceEnabled();
    }

    protected function isSchemaConfiguratorActive(): bool
    {
        return $this->getGraphQLEndpointHandler()->isEndpointRequested();
    }

    /**
     * This is the Schema Configuration ID.
     *
     * @return int|null The Schema Configuration ID, null if none was selected (in which case a default Schema Configuration can be applied), or -1 if "None" was selected (i.e. no default Schema Configuration must be applied)
     */
    protected function getSchemaConfigurationID(): ?int
    {
        // Return the stored Schema Configuration ID
        return $this->getEndpointBlockHelpers()->getUserSettingSchemaConfigurationID(EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT);
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getSingleEndpointSchemaConfigurator();
    }
}
