<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

trait ComponentFieldTrait
{
    /**
     * Allow doing `array_unique` based on the underlying Field,
     * and having the AST object be added as index in the array
     */
    public function __toString(): string
    {
        return $this->asFieldOutputQueryString();
    }

    abstract public function asFieldOutputQueryString(): string;
}
