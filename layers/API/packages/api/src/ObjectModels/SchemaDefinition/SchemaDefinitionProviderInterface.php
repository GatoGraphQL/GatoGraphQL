<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface SchemaDefinitionProviderInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getSchemaDefinition(): array;
}
