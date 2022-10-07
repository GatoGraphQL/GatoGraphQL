<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasFieldsTypeInterface extends NamedTypeInterface
{
    /**
     * @param bool $includeGlobal Custom parameter by this GraphQL Server (i.e. it is not present in the GraphQL spec)
     * @return Field[]
     */
    public function getFields(
        bool $includeDeprecated = false,
        bool $includeGlobal = true,
    ): array;

    /**
     * @param bool $includeGlobal Custom parameter by this GraphQL Server (i.e. it is not present in the GraphQL spec)
     * @return string[]
     */
    public function getFieldIDs(
        bool $includeDeprecated = false,
        bool $includeGlobal = true,
    ): array;
}
