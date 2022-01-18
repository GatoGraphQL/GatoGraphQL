<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

use PoP\Engine\Schema\SchemaDefinitionServiceInterface as UpstreamSchemaDefinitionServiceInterface;

interface SchemaDefinitionServiceInterface extends UpstreamSchemaDefinitionServiceInterface
{
    public function &getFullSchemaDefinition(): array;
    public function sortFullSchemaAlphabetically(array &$schemaDefinition): void;
}
