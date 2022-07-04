<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface InputObjectUnderFieldArgumentFieldDataProviderInterface extends FieldDataProviderInterface
{
    public function getArgumentName(): string;
}
