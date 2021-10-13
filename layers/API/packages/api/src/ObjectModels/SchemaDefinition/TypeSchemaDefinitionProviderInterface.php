<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;


interface TypeSchemaDefinitionProviderInterface extends SchemaDefinitionProviderInterface
{
    public function getType(): string;
    /**
     * @return array<string, RelationalTypeResolverInterface> Key: directive resolver class, Value: The Type Resolver Class which loads the directive
     */
    public function getAccessedDirectiveResolverClassRelationalTypeResolvers(): array;
}
