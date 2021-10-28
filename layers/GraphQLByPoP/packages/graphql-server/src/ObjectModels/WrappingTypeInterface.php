<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface WrappingTypeInterface extends TypeInterface, SchemaDefinitionReferenceObjectInterface
{
    public function getWrappedType(): TypeInterface;
}
