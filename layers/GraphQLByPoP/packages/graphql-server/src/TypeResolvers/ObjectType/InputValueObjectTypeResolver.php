<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\InputValue;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use Symfony\Contracts\Service\Attribute\Required;

class InputValueObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    protected SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader;

    #[Required]
    public function autowireInputValueObjectTypeResolver(
        SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader,
    ): void {
        $this->schemaDefinitionReferenceTypeDataLoader = $schemaDefinitionReferenceTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return '__InputValue';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of an input object in GraphQL', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var InputValue */
        $inputValue = $object;
        return $inputValue->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->schemaDefinitionReferenceTypeDataLoader;
    }
}
