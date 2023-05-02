<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use PoP\Root\Services\ServiceInterface;

interface SchemaConfigurationExecuterInterface extends ServiceInterface
{
    /**
     * Execute the schema configuration contained in the custom post with certain ID
     */
    public function executeSchemaConfiguration(int $schemaConfigurationID): void;
    /**
     * Execute logic when no schema configuration was selected
     */
    public function executeNoneAppliedSchemaConfiguration(): void;
}
