<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\Syntax\SyntaxHelpers;
use PoP\API\Schema\SchemaDefinition;

trait ResolveTypeSchemaDefinitionReferenceTrait
{
    protected function getTypeFromTypeName(string $typeName): TypeInterface
    {
        // Check if the type is non-null
        if (SyntaxHelpers::isNonNullType($typeName)) {
            return new NonNullType(
                $this->fullSchemaDefinition,
                $this->schemaDefinitionPath,
                $this->getTypeFromTypeName(
                    SyntaxHelpers::getNonNullTypeNestedTypeName($typeName)
                )
            );
        }

        // Check if it is an array
        if (SyntaxHelpers::isListType($typeName)) {
            return new ListType(
                $this->fullSchemaDefinition,
                $this->schemaDefinitionPath,
                $this->getTypeFromTypeName(
                    SyntaxHelpers::getListTypeNestedTypeName($typeName)
                )
            );
        }

        // Retrive the type from the registry
        $typeSchemaDefinitionPath = [
            SchemaDefinition::TYPES,
            $typeName,
        ];
        $schemaDefinitionID = SchemaDefinitionHelpers::getID($typeSchemaDefinitionPath);
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        /** @var TypeInterface */
        return $schemaDefinitionReferenceRegistry->getSchemaDefinitionReference($schemaDefinitionID);
    }
}
