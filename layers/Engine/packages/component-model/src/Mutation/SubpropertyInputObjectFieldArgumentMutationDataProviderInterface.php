<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

interface SubpropertyInputObjectFieldArgumentMutationDataProviderInterface extends InputObjectFieldArgumentMutationDataProviderInterface
{
    public function getSubpropertyName(): string;
}
