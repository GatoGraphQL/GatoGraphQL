<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface PropertyUnderInputObjectUnderFieldArgumentFieldDataAccessorInterface extends InputObjectUnderFieldArgumentFieldDataAccessorInterface
{
    public function getInputObjectPropertyName(): string;
}
