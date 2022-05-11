<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

trait ModuleFieldTrait
{
    /**
     * Allow doing `array_unique` based on the underlying Field
     */
    public function __toString(): string
    {
        return $this->asFieldOutputQueryString();
    }

    abstract public function asFieldOutputQueryString(): string;
}
