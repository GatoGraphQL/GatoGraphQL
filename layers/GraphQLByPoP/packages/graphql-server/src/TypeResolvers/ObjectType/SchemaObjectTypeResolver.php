<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class SchemaObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    private ?SchemaObjectTypeDataLoader $schemaObjectTypeDataLoader = null;

    final public function setSchemaObjectTypeDataLoader(SchemaObjectTypeDataLoader $schemaObjectTypeDataLoader): void
    {
        $this->schemaObjectTypeDataLoader = $schemaObjectTypeDataLoader;
    }
    final protected function getSchemaObjectTypeDataLoader(): SchemaObjectTypeDataLoader
    {
        /** @var SchemaObjectTypeDataLoader */
        return $this->schemaObjectTypeDataLoader ??= $this->instanceManager->getInstance(SchemaObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return '__Schema';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Schema type, to implement the introspection fields', 'graphql-server');
    }

    public function getID(object $object): string|int|null
    {
        /** @var Schema */
        $schema = $object;
        return $schema->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSchemaObjectTypeDataLoader();
    }
}
