<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasFieldsTypeInterface
{
    public function getFields(bool $includeDeprecated = false): array;
    public function getFieldIDs(bool $includeDeprecated = false): array;
}
