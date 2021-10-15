<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface SchemaDefinitionReferenceObjectInterface extends WrappingTypeOrSchemaDefinitionReferenceObjectInterface
{
    public function getSchemaDefinitionPath(): array;
}
