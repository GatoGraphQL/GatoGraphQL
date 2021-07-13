<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\AccessControlSchemaConfigurationExecuter;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\CacheControlSchemaConfigurationExecuter;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AbstractQueryExecutionSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\FieldDeprecationGraphQLQueryConfigurator;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class PersistedQuerySchemaConfigurator extends AbstractQueryExecutionSchemaConfigurator
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        AccessControlSchemaConfigurationExecuter $accessControlSchemaConfigurationExecuter,
        FieldDeprecationGraphQLQueryConfigurator $fieldDeprecationGraphQLQueryConfigurator,
        protected CacheControlSchemaConfigurationExecuter $cacheControlSchemaConfigurationExecuter
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $accessControlSchemaConfigurationExecuter,
            $fieldDeprecationGraphQLQueryConfigurator,
        );
    }

    /**
     * Apply all the settings defined in the Schema Configuration:
     * - Access Control Lists
     * - Cache Control Lists
     * - Field Deprecation Lists
     */
    protected function executeSchemaConfigurationItems(int $schemaConfigurationID): void
    {
        parent::executeSchemaConfigurationItems($schemaConfigurationID);

        // Execute the Cache Control
        $this->cacheControlSchemaConfigurationExecuter->executeSchemaConfiguration($schemaConfigurationID);
    }
}
