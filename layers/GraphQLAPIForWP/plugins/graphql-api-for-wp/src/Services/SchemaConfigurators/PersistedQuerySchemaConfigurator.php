<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\AccessControlListsSchemaConfigurationExecuter;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\CacheControlListsSchemaConfigurationExecuter;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AbstractQueryExecutionSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AccessControlGraphQLQueryConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\FieldDeprecationGraphQLQueryConfigurator;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class PersistedQuerySchemaConfigurator extends AbstractQueryExecutionSchemaConfigurator
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        AccessControlListsSchemaConfigurationExecuter $accessControlListsSchemaConfigurationExecuter,
        FieldDeprecationGraphQLQueryConfigurator $fieldDeprecationGraphQLQueryConfigurator,
        protected CacheControlListsSchemaConfigurationExecuter $cacheControlListsSchemaConfigurationExecuter
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $accessControlListsSchemaConfigurationExecuter,
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
        $this->cacheControlListsSchemaConfigurationExecuter->executeSchemaConfiguration($schemaConfigurationID);
    }
}
