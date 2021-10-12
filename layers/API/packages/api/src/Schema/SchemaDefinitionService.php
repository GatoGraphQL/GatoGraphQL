<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use PoP\Engine\Schema\SchemaDefinitionService as UpstreamSchemaDefinitionService;

class SchemaDefinitionService extends UpstreamSchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    /**
     * @todo Implement
     */
    public function getFullSchemaDefinition(): array
    {
        return [];
    }
}
