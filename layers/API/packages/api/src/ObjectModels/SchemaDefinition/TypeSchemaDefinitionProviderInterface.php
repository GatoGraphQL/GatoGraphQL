<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;


interface TypeSchemaDefinitionProviderInterface extends SchemaDefinitionProviderInterface
{
    public function getType(): string;
}
