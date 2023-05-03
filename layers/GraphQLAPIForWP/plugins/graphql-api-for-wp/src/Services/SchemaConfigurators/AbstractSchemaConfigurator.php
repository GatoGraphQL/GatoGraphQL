<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;
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
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }

    public function isServiceEnabled(): bool
    {
        $moduleRegistry = $this->getModuleRegistry();
        if (!$moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION)) {
            return false;
        }

        // Only enable the service if the corresponding module is also enabled
        return $moduleRegistry->isModuleEnabled($this->getEnablingModule());
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
