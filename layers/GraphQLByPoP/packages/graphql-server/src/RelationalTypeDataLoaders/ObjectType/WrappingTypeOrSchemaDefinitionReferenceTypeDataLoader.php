<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\ListType;
use GraphQLByPoP\GraphQLServer\ObjectModels\NonNullType;
use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeOrSchemaDefinitionReferenceObjectInterface;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\Syntax\SyntaxHelpers;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader extends AbstractObjectTypeDataLoader
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
     * @return array<WrappingTypeInterface | SchemaDefinitionReferenceObjectInterface>
     */
    public function getObjects(array $ids): array
    {
        return array_map(
            fn (string $typeID) => $this->getWrappingTypeOrSchemaDefinitionReferenceObject($typeID),
            $ids
        );
    }

    protected function getWrappingTypeOrSchemaDefinitionReferenceObject(string $typeID): WrappingTypeInterface | SchemaDefinitionReferenceObjectInterface
    {
        // Check if the type is non-null
        if (SyntaxHelpers::isNonNullWrappingType($typeID)) {
            /** @var TypeInterface */
            $wrappedType = $this->getWrappingTypeOrSchemaDefinitionReferenceObject(
                SyntaxHelpers::extractWrappedTypeFromNonNullWrappingType($typeID)
            );
            return new NonNullType($wrappedType);
        }

        // Check if it is an array
        if (SyntaxHelpers::isListWrappingType($typeID)) {
            /** @var TypeInterface */
            $wrappedType = $this->getWrappingTypeOrSchemaDefinitionReferenceObject(
                SyntaxHelpers::extractWrappedTypeFromListWrappingType($typeID)
            );
            return new ListType($wrappedType);
        }

        return $this->schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($typeID);
    }
}
