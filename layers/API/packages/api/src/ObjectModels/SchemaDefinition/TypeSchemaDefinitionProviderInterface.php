<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface TypeSchemaDefinitionProviderInterface extends SchemaDefinitionProviderInterface
{
    public function getTypeKind(): string;
    /**
     * @return array<string, RelationalTypeResolverInterface> Key: directive resolver class, Value: The Type Resolver Class which loads the directive
     */
    public function getAccessedDirectiveResolverClassRelationalTypeResolvers(): array;
}
