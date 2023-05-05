<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfiguratorExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointBlockHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\PrivateEndpointSchemaConfigurator;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers\GraphQLEndpointHandler;

class PrivateEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?PrivateEndpointSchemaConfigurator $privateEndpointSchemaConfigurator = null;
    private ?GraphQLEndpointHandler $graphQLEndpointHandler = null;
    private ?EndpointBlockHelpers $endpointBlockHelpers = null;
    private ?EndpointHelpers $endpointHelpers = null;

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
    final public function setPrivateEndpointSchemaConfigurator(PrivateEndpointSchemaConfigurator $privateEndpointSchemaConfigurator): void
    {
        $this->privateEndpointSchemaConfigurator = $privateEndpointSchemaConfigurator;
    }
    final protected function getPrivateEndpointSchemaConfigurator(): PrivateEndpointSchemaConfigurator
    {
        /** @var PrivateEndpointSchemaConfigurator */
        return $this->privateEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PrivateEndpointSchemaConfigurator::class);
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
    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }

    protected function isSchemaConfiguratorActive(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        /**
         * Only enable it when executing a query against the private endpoint
         * or the InternalGraphQLServer
         */
        if (
            !(
            $this->getEndpointHelpers()->isRequestingDefaultAdminGraphQLEndpoint()
            || ($moduleConfiguration->useSchemaConfigurationInInternalGraphQLServer()
                && AppHelpers::isInternalGraphQLServerAppThread()
            )
            )
        ) {
            return false;
        }
        return true;
    }

    /**
     * This is the Schema Configuration ID.
     *
     * @return int|null The Schema Configuration ID, null if none was selected (in which case a default Schema Configuration can be applied), or -1 if "None" was selected (i.e. no default Schema Configuration must be applied)
     */
    protected function getSchemaConfigurationID(): ?int
    {
        // Return the stored Schema Configuration ID
        return $this->getEndpointBlockHelpers()->getUserSettingSchemaConfigurationID(EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT);
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPrivateEndpointSchemaConfigurator();
    }
}
