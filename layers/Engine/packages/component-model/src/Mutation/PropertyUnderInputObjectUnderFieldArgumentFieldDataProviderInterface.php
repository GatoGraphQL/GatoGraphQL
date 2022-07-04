<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface PropertyUnderInputObjectUnderFieldArgumentFieldDataProviderInterface extends InputObjectUnderFieldArgumentFieldDataProviderInterface
{
    public function getInputObjectPropertyName(): string;
}
