<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

interface InputObjectUnderFieldArgumentFieldDataAccessorInterface extends FieldDataAccessorInterface
{
    public function getArgumentName(): string;
}
