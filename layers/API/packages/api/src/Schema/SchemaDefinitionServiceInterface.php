<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use PoP\Engine\Schema\SchemaDefinitionServiceInterface as UpstreamSchemaDefinitionServiceInterface;

interface SchemaDefinitionServiceInterface extends UpstreamSchemaDefinitionServiceInterface
{
    public function &getFullSchemaDefinition(): array;
}
