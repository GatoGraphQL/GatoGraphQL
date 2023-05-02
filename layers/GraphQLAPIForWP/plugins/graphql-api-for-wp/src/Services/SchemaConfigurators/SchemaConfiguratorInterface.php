<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use PoP\Root\Services\ServiceInterface;

interface SchemaConfiguratorInterface extends ServiceInterface
{
    /**
     * Execute the schema configuration with certain ID
     */
    public function executeSchemaConfiguration(int $schemaConfigurationID): void;
    /**
     * Indicate if the Configurator can be executed when the
     * endpoint does not have a Schema Configuration assigned
     */
    public function needsSchemaConfigurationToBeExecuted(): bool;
}
