<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeExtensions;

interface NamedTypeInterface extends TypeInterface, SchemaDefinitionReferenceObjectInterface
{
    public function getNamespacedName(): string;

    public function getElementName(): string;

    public function getExtensions(): NamedTypeExtensions;
}
