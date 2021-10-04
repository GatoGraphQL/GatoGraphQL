<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use Symfony\Contracts\Service\Attribute\Required;

class DirectiveObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    protected SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader;

    #[Required]
    final public function autowireDirectiveObjectTypeResolver(
        SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader,
    ): void {
        $this->schemaDefinitionReferenceTypeDataLoader = $schemaDefinitionReferenceTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return '__Directive';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('A GraphQL directive in the data graph', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var Directive */
        $directive = $object;
        return $directive->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->schemaDefinitionReferenceTypeDataLoader;
    }
}
