<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\SchemaConfigurationExecuterRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractSchemaConfigurator implements SchemaConfiguratorInterface
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
     * Important! Do not check if the SCHEMA_CONFIGURATION module
     * is enabled, as to configure the schema with default Settings.
     *
     * That check will happen in AbstractSchemaConfigurationExecuter,
     * where each executer can decide if to run or not.
     *
     * @see layers/GatoGraphQLForWP/plugins/gatographql/src/Services/SchemaConfigurationExecuters/AbstractSchemaConfigurationExecuter.php
     */
    public function isServiceEnabled(): bool
    {
        /**
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->disableSchemaConfiguration()) {
            return false;
        }

        // Only enable the service if the corresponding module is also enabled
        return $this->getModuleRegistry()->isModuleEnabled($this->getEnablingModule());
    }

    abstract protected function getEnablingModule(): string;

    /**
     * Extract the items defined in the Schema Configuration,
     * and inject them into the service as to take effect
     * in the current GraphQL query
     */
    final public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        // Only if the module is not disabled
        if (!$this->isServiceEnabled()) {
            return;
        }

        // Get that Schema Configuration, and load its settings
        $this->doExecuteSchemaConfiguration($schemaConfigurationID);
    }

    protected function doExecuteSchemaConfiguration(int $schemaConfigurationID): void
    {
        foreach ($this->getSchemaConfigurationExecuterRegistry()->getEnabledSchemaConfigurationExecuters() as $schemaConfigurationExecuter) {
            $schemaConfigurationExecuter->executeSchemaConfiguration($schemaConfigurationID);
        }
    }

    final public function executeNoneAppliedSchemaConfiguration(): void
    {
        // Only if the module is not disabled
        if (!$this->isServiceEnabled()) {
            return;
        }

        $this->doExecuteNoneAppliedSchemaConfiguration();
    }

    protected function doExecuteNoneAppliedSchemaConfiguration(): void
    {
        foreach ($this->getSchemaConfigurationExecuterRegistry()->getEnabledSchemaConfigurationExecuters() as $schemaConfigurationExecuter) {
            $schemaConfigurationExecuter->executeNoneAppliedSchemaConfiguration();
        }
    }

    abstract protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface;
}
