<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasFieldsTypeInterface extends NamedTypeInterface
{
    /**
     * @return Field[]
     */
    public function getFields(bool $includeDeprecated = false): array;
    /**
     * @return string[]
     */
    public function getFieldIDs(bool $includeDeprecated = false): array;
}
