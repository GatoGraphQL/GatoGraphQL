<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use Symfony\Contracts\Service\Attribute\Required;

class TypeObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    private ?WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader = null;

    public function setWrappingTypeOrSchemaDefinitionReferenceTypeDataLoader(WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader): void
    {
        $this->wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader = $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader;
    }
    protected function getWrappingTypeOrSchemaDefinitionReferenceTypeDataLoader(): WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader
    {
        return $this->wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader ??= $this->instanceManager->getInstance(WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader::class);
    }

    //#[Required]
    final public function autowireTypeObjectTypeResolver(
        WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader,
    ): void {
        $this->wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader = $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader;
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
        /** @var TypeInterface */
        $type = $object;
        return $type->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getWrappingTypeOrSchemaDefinitionReferenceTypeDataLoader();
    }
}
