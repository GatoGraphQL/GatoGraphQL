<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

interface PropertyUnderInputObjectUnderFieldArgumentFieldDataAccessorInterface extends InputObjectUnderFieldArgumentFieldDataAccessorInterface
{
    public function getInputObjectPropertyName(): string;
}
