<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;

interface QueryHelperServiceInterface
{
    public function isDynamicVariableReference(
        string $name,
        ?Variable $variable,
    ): bool;
}
