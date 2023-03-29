<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class DirectiveObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
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
        return '__Directive';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('A GraphQL directive in the data graph', 'graphql-server');
    }

    public function getID(object $object): string|int|null
    {
        /** @var Directive */
        $directive = $object;
        return $directive->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSchemaDefinitionReferenceObjectTypeDataLoader();
    }
}
