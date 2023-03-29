<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\InputValue;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class InputValueObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    private ?SchemaDefinitionReferenceObjectTypeDataLoader $schemaDefinitionReferenceObjectTypeDataLoader = null;

    final public function setSchemaDefinitionReferenceObjectTypeDataLoader(SchemaDefinitionReferenceObjectTypeDataLoader $schemaDefinitionReferenceObjectTypeDataLoader): void
    {
        $this->schemaDefinitionReferenceObjectTypeDataLoader = $schemaDefinitionReferenceObjectTypeDataLoader;
    }
    final protected function getSchemaDefinitionReferenceObjectTypeDataLoader(): SchemaDefinitionReferenceObjectTypeDataLoader
    {
        /** @var SchemaDefinitionReferenceObjectTypeDataLoader */
        return $this->schemaDefinitionReferenceObjectTypeDataLoader ??= $this->instanceManager->getInstance(SchemaDefinitionReferenceObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return '__InputValue';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of an input object in GraphQL', 'graphql-server');
    }

    public function getID(object $object): string|int|null
    {
        /** @var InputValue */
        $inputValue = $object;
        return $inputValue->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSchemaDefinitionReferenceObjectTypeDataLoader();
    }
}
