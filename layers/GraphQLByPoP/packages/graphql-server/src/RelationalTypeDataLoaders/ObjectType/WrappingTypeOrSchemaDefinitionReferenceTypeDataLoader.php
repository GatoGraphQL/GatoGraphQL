<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\ListWrappingType;
use GraphQLByPoP\GraphQLServer\ObjectModels\NonNullWrappingType;
use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeInterface;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\Syntax\GraphQLSyntaxServiceInterface;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

class WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry = null;
    private ?GraphQLSyntaxServiceInterface $graphQLSyntaxService = null;
    private ?ObjectDictionaryInterface $objectDictionary = null;

    final public function setSchemaDefinitionReferenceRegistry(SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry): void
    {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }
    final protected function getSchemaDefinitionReferenceRegistry(): SchemaDefinitionReferenceRegistryInterface
    {
        return $this->schemaDefinitionReferenceRegistry ??= $this->instanceManager->getInstance(SchemaDefinitionReferenceRegistryInterface::class);
    }
    final public function setGraphQLSyntaxService(GraphQLSyntaxServiceInterface $graphQLSyntaxService): void
    {
        $this->graphQLSyntaxService = $graphQLSyntaxService;
    }
    final protected function getGraphQLSyntaxService(): GraphQLSyntaxServiceInterface
    {
        return $this->graphQLSyntaxService ??= $this->instanceManager->getInstance(GraphQLSyntaxServiceInterface::class);
    }
    final public function setObjectDictionary(ObjectDictionaryInterface $objectDictionary): void
    {
        $this->objectDictionary = $objectDictionary;
    }
    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        return $this->objectDictionary ??= $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
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
        // Check if the type is non-null or an array
        $isNonNullWrappingType = $this->getGraphQLSyntaxService()->isNonNullWrappingType($typeID);
        if (
            $isNonNullWrappingType
            || $this->getGraphQLSyntaxService()->isListWrappingType($typeID)
        ) {
            // Store the single WrappingType instance in a dictionary
            $objectTypeResolverClass = get_class();
            if ($this->getObjectDictionary()->has($objectTypeResolverClass, $typeID)) {
                return $this->getObjectDictionary()->get($objectTypeResolverClass, $typeID);
            }
            $wrappingType = null;
            if ($isNonNullWrappingType) {
                /** @var TypeInterface */
                $wrappedType = $this->getWrappingTypeOrSchemaDefinitionReferenceObject(
                    $this->getGraphQLSyntaxService()->extractWrappedTypeFromNonNullWrappingType($typeID)
                );
                $wrappingType = new NonNullWrappingType($wrappedType);
            } else {
                /** @var TypeInterface */
                $wrappedType = $this->getWrappingTypeOrSchemaDefinitionReferenceObject(
                    $this->getGraphQLSyntaxService()->extractWrappedTypeFromListWrappingType($typeID)
                );
                $wrappingType = new ListWrappingType($wrappedType);
            }
            $this->getObjectDictionary()->set($objectTypeResolverClass, $typeID, $wrappingType);
            return $wrappingType;
        }

        return $this->getSchemaDefinitionReferenceRegistry()->getSchemaDefinitionReferenceObject($typeID);
    }
}
