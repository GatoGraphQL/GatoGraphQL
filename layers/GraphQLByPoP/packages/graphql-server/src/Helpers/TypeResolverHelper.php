<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Helpers;

class TypeResolverHelper implements TypeResolverHelperInterface
{
    /**
     * Return the list of fieldNames that are mandatory to all ObjectTypeResolvers
     *
     * @return string[]
     */
    public function getObjectTypeResolverMandatoryFields(): array
    {
        return [
            // These ones are not part anymore from MutationRoot, so commented code
            // 'id',
            // 'globalID',
            // 'self',
            '__typename',
        ];
    }
}
