<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface WrappingTypeInterface extends TypeInterface
{
    public function getWrappedType(): TypeInterface;
    public function getWrappedTypeID(): string;
}
