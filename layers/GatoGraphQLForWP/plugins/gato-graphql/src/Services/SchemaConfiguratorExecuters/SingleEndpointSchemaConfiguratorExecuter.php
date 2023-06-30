<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfiguratorExecuters;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointBlockHelpers;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\SingleEndpointSchemaConfigurator;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
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
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final public function setSingleEndpointSchemaConfigurator(SingleEndpointSchemaConfigurator $singleEndpointSchemaConfigurator): void
    {
        $this->singleEndpointSchemaConfigurator = $singleEndpointSchemaConfigurator;
    }
    final protected function getSingleEndpointSchemaConfigurator(): SingleEndpointSchemaConfigurator
    {
        if ($this->singleEndpointSchemaConfigurator === null) {
            /** @var SingleEndpointSchemaConfigurator */
            $singleEndpointSchemaConfigurator = $this->instanceManager->getInstance(SingleEndpointSchemaConfigurator::class);
            $this->singleEndpointSchemaConfigurator = $singleEndpointSchemaConfigurator;
        }
        return $this->singleEndpointSchemaConfigurator;
    }
    final public function setGraphQLEndpointHandler(GraphQLEndpointHandler $graphQLEndpointHandler): void
    {
        $this->graphQLEndpointHandler = $graphQLEndpointHandler;
    }
    final protected function getGraphQLEndpointHandler(): GraphQLEndpointHandler
    {
        if ($this->graphQLEndpointHandler === null) {
            /** @var GraphQLEndpointHandler */
            $graphQLEndpointHandler = $this->instanceManager->getInstance(GraphQLEndpointHandler::class);
            $this->graphQLEndpointHandler = $graphQLEndpointHandler;
        }
        return $this->graphQLEndpointHandler;
    }
    final public function setEndpointBlockHelpers(EndpointBlockHelpers $endpointBlockHelpers): void
    {
        $this->endpointBlockHelpers = $endpointBlockHelpers;
    }
    final protected function getEndpointBlockHelpers(): EndpointBlockHelpers
    {
        if ($this->endpointBlockHelpers === null) {
            /** @var EndpointBlockHelpers */
            $endpointBlockHelpers = $this->instanceManager->getInstance(EndpointBlockHelpers::class);
            $this->endpointBlockHelpers = $endpointBlockHelpers;
        }
        return $this->endpointBlockHelpers;
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
