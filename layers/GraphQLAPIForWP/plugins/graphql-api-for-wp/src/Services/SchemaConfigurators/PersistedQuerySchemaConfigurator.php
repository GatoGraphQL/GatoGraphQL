<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQuerySchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AbstractQueryExecutionSchemaConfigurator;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class PersistedQuerySchemaConfigurator extends AbstractQueryExecutionSchemaConfigurator
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected PersistedQuerySchemaConfigurationExecuterRegistryInterface $persistedQuerySchemaConfigurationExecuterRegistry
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    protected function executeSchemaConfigurationItems(int $schemaConfigurationID): void
    {
        foreach ($this->persistedQuerySchemaConfigurationExecuterRegistry->getSchemaConfigurationExecuters() as $schemaConfigurationExecuter) {
            $schemaConfigurationExecuter->executeSchemaConfiguration($schemaConfigurationID);
        }
    }
}
