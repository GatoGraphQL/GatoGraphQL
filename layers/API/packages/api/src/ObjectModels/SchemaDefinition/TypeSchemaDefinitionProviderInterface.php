<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface TypeSchemaDefinitionProviderInterface
{
    public function getType(): string;
    /**
     * @return array<string, mixed>
     */
    public function getSchemaDefinition(): array;
    /**
     * An array of typeName => typeResolver
     * 
     * @return array<string, TypeResolverInterface>
     */
    public function getAccessedTypeResolvers(): array;
}
