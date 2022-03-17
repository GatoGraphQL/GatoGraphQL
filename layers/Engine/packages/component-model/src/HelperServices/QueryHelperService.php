<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\Constants\QuerySyntax;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;

class QueryHelperService implements QueryHelperServiceInterface
{
    public function isDynamicVariableReference(
        string $name,
        ?Variable $variable,
    ): bool {
        return $variable === null
            && \str_starts_with(
                $name,
                QuerySyntax::DYNAMIC_VARIABLE_NAME_PREFIX
            );
    }
}
