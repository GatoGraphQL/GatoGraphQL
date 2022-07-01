<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface PropertyUnderInputObjectUnderFieldArgumentMutationDataProviderInterface extends InputObjectUnderFieldArgumentMutationDataProviderInterface
{
    public function getInputObjectPropertyName(): string;
}
