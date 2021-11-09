<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class SchemaObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    private ?SchemaTypeDataLoader $schemaTypeDataLoader = null;

    final public function setSchemaTypeDataLoader(SchemaTypeDataLoader $schemaTypeDataLoader): void
    {
        $this->schemaTypeDataLoader = $schemaTypeDataLoader;
    }
    final protected function getSchemaTypeDataLoader(): SchemaTypeDataLoader
    {
        return $this->schemaTypeDataLoader ??= $this->instanceManager->getInstance(SchemaTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return '__Schema';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Schema type, to implement the introspection fields', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var Schema */
        $schema = $object;
        return $schema->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSchemaTypeDataLoader();
    }
}
