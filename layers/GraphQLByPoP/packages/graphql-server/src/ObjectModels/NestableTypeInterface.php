<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface NestableTypeInterface extends TypeInterface
{
    public function getNestedType(): TypeInterface;
    public function getNestedTypeID(): string;
}
