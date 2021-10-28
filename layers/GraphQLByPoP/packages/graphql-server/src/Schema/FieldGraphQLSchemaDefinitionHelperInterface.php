FieldGraphQLSchemaDefinitionHelperFieldGraphQLSchemaDefinitionHelper<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\ObjectModels\Field;

interface FieldGraphQLSchemaDefinitionHelperInterface
{
    /**
     * @return Field[]
     */
    public function createFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array;
    /**
     * @return Field[]
     */
    public function getFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array;
}
