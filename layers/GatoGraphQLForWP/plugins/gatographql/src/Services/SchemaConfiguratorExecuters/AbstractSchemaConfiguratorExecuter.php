<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfiguratorExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointSchemaConfigurationBlock;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\Root\Module\ApplicationEvents;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractSchemaConfiguratorExecuter extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

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

    /**
     * Execute before all the services are attached,
     * as to use this configuration to affect these.
     *
     * For instance, the Queryable Custom Post Types can be
     * configured in the Schema Configuration, and from this list
     * will the ObjectTypeResolverPicker for the GenericCustomPost
     * decide if to add it to the CustomPostUnion or not. Hence,
     * this service must be executed before the Attachable services
     * are executed.
     */
    public function getInstantiationEvent(): string
    {
        return ApplicationEvents::PRE_BOOT;
    }

    public function isServiceEnabled(): bool
    {
        if (!$this->getModuleRegistry()->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION)) {
            return false;
        }
        
        /**
         * Maybe do not initialize for the Internal AppThread
         *
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            !$moduleConfiguration->useSchemaConfigurationInInternalGraphQLServer()
            && AppHelpers::isInternalGraphQLServerAppThread()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Initialize the configuration if a certain condition is satisfied
     */
    public function initialize(): void
    {
        if (!$this->isSchemaConfiguratorActive()) {
            return;
        }
        $schemaConfigurationID = $this->getSchemaConfigurationID();
        if (
            $schemaConfigurationID === EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE
            && $this->doesSchemaConfigurationNoneSkipApplyingTheDefaultSettings()
        ) {
            return;
        }
        $schemaConfigurator = $this->getSchemaConfigurator();
        if ($schemaConfigurationID === null) {
            $schemaConfigurator->executeNoneAppliedSchemaConfiguration();
            return;
        }
        $schemaConfigurator->executeSchemaConfiguration($schemaConfigurationID);
    }


    /**
     * Selecting Schema Configuration "None" (with artificial ID "-1")
     * also applies the default Settings.
     *
     * This is because otherwise this default behavior (which is executed
     * in the SchemaConfigurationExecuter, for ACLs, CCLs and FDLs)
     * would be different from the default settings added in
     * PluginInitializationConfiguration (Namespacing, Nested mutations, etc),
     * which are always executed.
     *
     * This is a legacy method, kept for documentation purposes.
     */
    private function doesSchemaConfigurationNoneSkipApplyingTheDefaultSettings(): bool
    {
        return false;
    }

    abstract protected function isSchemaConfiguratorActive(): bool;

    /**
     * Provide the ID of the custom post containing the Schema Configuration block.
     *
     * @return int|null The Schema Configuration ID, null if none was selected (in which case a default Schema Configuration can be applied), or -1 if "None" was selected (i.e. no default Schema Configuration must be applied)
     */
    abstract protected function getSchemaConfigurationID(): ?int;

    /**
     * Initialize the configuration of services before the execution of the GraphQL query
     */
    abstract protected function getSchemaConfigurator(): SchemaConfiguratorInterface;
}
