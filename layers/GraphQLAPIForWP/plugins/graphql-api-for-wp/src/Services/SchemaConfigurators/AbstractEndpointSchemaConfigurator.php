<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractEndpointSchemaConfigurator implements SchemaConfiguratorInterface
{
    protected InstanceManagerInterface $instanceManager;
    protected ModuleRegistryInterface $moduleRegistry;
    protected BlockHelpers $blockHelpers;

    #[Required]
    final public function autowireAbstractEndpointSchemaConfigurator(InstanceManagerInterface $instanceManager, ModuleRegistryInterface $moduleRegistry, BlockHelpers $blockHelpers): void
    {
        $this->instanceManager = $instanceManager;
        $this->moduleRegistry = $moduleRegistry;
        $this->blockHelpers = $blockHelpers;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        return $this->moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION);
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
