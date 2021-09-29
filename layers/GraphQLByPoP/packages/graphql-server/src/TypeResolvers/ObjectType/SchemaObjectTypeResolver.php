<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    protected SchemaTypeDataLoader $schemaTypeDataLoader;

    #[Required]
    public function autowireSchemaObjectTypeResolver(SchemaTypeDataLoader $schemaTypeDataLoader): void
    {
        $this->schemaTypeDataLoader = $schemaTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return '__Schema';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Schema type, to implement the introspection fields', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var Schema */
        $schema = $object;
        return $schema->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->schemaTypeDataLoader;
    }
}
