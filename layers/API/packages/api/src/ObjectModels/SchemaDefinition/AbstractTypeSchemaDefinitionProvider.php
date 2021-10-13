<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

abstract class AbstractTypeSchemaDefinitionProvider implements TypeSchemaDefinitionProviderInterface
{
    public function getAccessedTypeResolvers(): array
    {
        return [];
    }
}
