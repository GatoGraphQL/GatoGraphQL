<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface TypeWrapperInterface extends TypeInterface
{
    public function getWrappedType(): TypeInterface;
    public function getWrappedTypeID(): string;
}
