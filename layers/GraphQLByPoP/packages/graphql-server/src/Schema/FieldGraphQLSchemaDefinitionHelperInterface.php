<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeInterface;

interface FieldGraphQLSchemaDefinitionHelperInterface
{
    /**
     * @return array<Field|WrappingTypeInterface>
     */
    public function createFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array;
    /**
     * @return array<Field|WrappingTypeInterface>
     */
    public function getFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array;
}
