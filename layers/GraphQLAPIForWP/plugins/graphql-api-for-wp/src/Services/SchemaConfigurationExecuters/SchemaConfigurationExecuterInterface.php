<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

interface SchemaConfigurationExecuterInterface
{
    /**
     * Execute the schema configuration contained in the custom post with certain ID
     */
    public function executeSchemaConfiguration(int $schemaConfigurationID): void;
}
