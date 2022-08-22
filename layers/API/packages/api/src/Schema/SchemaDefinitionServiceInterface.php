<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

use PoP\Engine\Schema\SchemaDefinitionServiceInterface as UpstreamSchemaDefinitionServiceInterface;

interface SchemaDefinitionServiceInterface extends UpstreamSchemaDefinitionServiceInterface
{
    /**
     * @return mixed[]
     */
    public function &getFullSchemaDefinition(): array;
    /**
     * @param array<string,mixed> $schemaDefinition
     */
    public function sortFullSchemaAlphabetically(array &$schemaDefinition): void;
}
