<?php

declare(strict_types=1);

namespace PoP\Engine\StaticHelpers;

use PoP\GraphQLParser\Spec\Parser\Ast\OperationTypes;

class SuperRootHelper
{
    public static function getOperationFromSuperRootFieldName(
        string $fieldName,
    ): ?string {
        return match ($fieldName) {
            '_rootForQueryRoot',
            '_queryRoot'
                => OperationTypes::QUERY,
            '_rootForMutationRoot',
            '_mutationRoot'
                => OperationTypes::MUTATION,
            default => null,
        };
    }
}
