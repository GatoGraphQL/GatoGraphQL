<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\SchemaConfigurators\SchemaConfiguratorInterface;

abstract class AbstractSchemaConfiguratorExecuter
{
    /**
     * Initialize the configuration if a certain condition is satisfied
     */
    public function init(): void
    {
        if ($customPostID = $this->getCustomPostID()) {
            $schemaConfigurator = $this->getSchemaConfigurator();
            $schemaConfigurator->executeSchemaConfiguration($customPostID);
        }
    }

    /**
     * Provide the ID of the custom post containing the Schema Configuration block
     */
    abstract protected function getCustomPostID(): ?int;

    /**
     * Initialize the configuration of services before the execution of the GraphQL query
     */
    abstract protected function getSchemaConfigurator(): SchemaConfiguratorInterface;
}
