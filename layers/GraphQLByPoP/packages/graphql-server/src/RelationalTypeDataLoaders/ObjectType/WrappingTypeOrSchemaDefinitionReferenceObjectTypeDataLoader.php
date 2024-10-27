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
use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

class WrappingTypeOrSchemaDefinitionReferenceObjectTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry = null;
    private ?GraphQLSyntaxServiceInterface $graphQLSyntaxService = null;
    private ?ObjectDictionaryInterface $objectDictionary = null;

    final protected function getSchemaDefinitionReferenceRegistry(): SchemaDefinitionReferenceRegistryInterface
    {
        if ($this->schemaDefinitionReferenceRegistry === null) {
            /** @var SchemaDefinitionReferenceRegistryInterface */
            $schemaDefinitionReferenceRegistry = $this->instanceManager->getInstance(SchemaDefinitionReferenceRegistryInterface::class);
            $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
        }
        return $this->schemaDefinitionReferenceRegistry;
    }
    final protected function getGraphQLSyntaxService(): GraphQLSyntaxServiceInterface
    {
        if ($this->graphQLSyntaxService === null) {
            /** @var GraphQLSyntaxServiceInterface */
            $graphQLSyntaxService = $this->instanceManager->getInstance(GraphQLSyntaxServiceInterface::class);
            $this->graphQLSyntaxService = $graphQLSyntaxService;
        }
        return $this->graphQLSyntaxService;
    }
    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        if ($this->objectDictionary === null) {
            /** @var ObjectDictionaryInterface */
            $objectDictionary = $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
            $this->objectDictionary = $objectDictionary;
        }
        return $this->objectDictionary;
    }

    /**
     * The IDs can contain GraphQL's type wrappers, such as `[String]!`
     *
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        /** @var string[] $ids */
        return array_map(
            $this->getWrappingTypeOrSchemaDefinitionReferenceObject(...),
            $ids
        );
    }

    protected function getWrappingTypeOrSchemaDefinitionReferenceObject(string $typeID): WrappingTypeInterface|SchemaDefinitionReferenceObjectInterface|null
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
