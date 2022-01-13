<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractEndpointSchemaConfigurator implements SchemaConfiguratorInterface
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?BlockHelpers $blockHelpers = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        return $this->blockHelpers ??= $this->instanceManager->getInstance(BlockHelpers::class);
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        return $this->getModuleRegistry()->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION);
    }

    /**
     * Extract the items defined in the Schema Configuration,
     * and inject them into the service as to take effect in the current GraphQL query
     */
    public function executeSchemaConfiguration(int $customPostID): void
    {
        // Only if the module is not disabled
        if (!$this->isServiceEnabled()) {
            return;
        }

        $this->doExecuteSchemaConfiguration($customPostID);
    }

    /**
     * Extract the items defined in the Schema Configuration,
     * and inject them into the service as to take effect in the current GraphQL query
     */
    protected function doExecuteSchemaConfiguration(int $customPostID): void
    {
        if ($schemaConfigurationID = $this->getSchemaConfigurationID($customPostID)) {
            // Get that Schema Configuration, and load its settings
            $this->executeSchemaConfigurationItems($schemaConfigurationID);
        }
    }

    abstract protected function getSchemaConfigurationID(int $customPostID): ?int;

    protected function executeSchemaConfigurationItems(int $schemaConfigurationID): void
    {
        foreach ($this->getSchemaConfigurationExecuterRegistry()->getEnabledSchemaConfigurationExecuters() as $schemaConfigurationExecuter) {
            $schemaConfigurationExecuter->executeSchemaConfiguration($schemaConfigurationID);
        }
    }

    abstract protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface;
}
