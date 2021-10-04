<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use Symfony\Contracts\Service\Attribute\Required;

class TypeObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    protected SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader;

    #[Required]
    public function autowireTypeObjectTypeResolver(
        SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader,
    ): void {
        $this->schemaDefinitionReferenceTypeDataLoader = $schemaDefinitionReferenceTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return '__Type';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of each GraphQL type in the graph', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var AbstractType */
        $type = $object;
        return $type->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->schemaDefinitionReferenceTypeDataLoader;
    }
}
