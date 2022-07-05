<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface InputObjectUnderFieldArgumentFieldDataAccessorInterface extends FieldDataAccessorInterface
{
    public function getArgumentName(): string;
}
