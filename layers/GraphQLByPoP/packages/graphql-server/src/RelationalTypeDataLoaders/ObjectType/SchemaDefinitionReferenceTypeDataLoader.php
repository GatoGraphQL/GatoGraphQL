<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\ListType;
use GraphQLByPoP\GraphQLServer\ObjectModels\NonNullType;
use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\Syntax\SyntaxHelpers;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionReferenceTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry;

    #[Required]
    final public function autowireSchemaDefinitionReferenceTypeDataLoader(
        SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry,
    ): void {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }

    /**
     * The IDs can contain GraphQL's type wrappers, such as `[String]!`
     * 
     * @return SchemaDefinitionReferenceObjectInterface[]
     */
    public function getObjects(array $ids): array
    {
        return array_map(
            fn (string $typeID) => $this->getSchemaDefinitionReferenceObject($typeID),
            $ids
        );
    }

    protected function getSchemaDefinitionReferenceObject(string $typeID): SchemaDefinitionReferenceObjectInterface
    {
        // Check if the type is non-null
        if (SyntaxHelpers::isNonNullType($typeID)) {
            return new NonNullType(
                $this->fullSchemaDefinition,
                $this->schemaDefinitionPath,
                $this->getSchemaDefinitionReferenceObject(
                    SyntaxHelpers::getNonNullTypeNestedTypeName($typeID)
                )
            );
        }

        // Check if it is an array
        if (SyntaxHelpers::isListType($typeID)) {
            return new ListType(
                $this->fullSchemaDefinition,
                $this->schemaDefinitionPath,
                $this->getSchemaDefinitionReferenceObject(
                    SyntaxHelpers::getListTypeNestedTypeName($typeID)
                )
            );
        }

        return $this->schemaDefinitionReferenceRegistry->getSchemaDefinitionReference($typeID);
    }
}
