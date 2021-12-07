<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveExtensions;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class DirectiveExtensionsObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    private ?SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader = null;

    final public function setSchemaDefinitionReferenceTypeDataLoader(SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader): void
    {
        $this->schemaDefinitionReferenceTypeDataLoader = $schemaDefinitionReferenceTypeDataLoader;
    }
    final protected function getSchemaDefinitionReferenceTypeDataLoader(): SchemaDefinitionReferenceTypeDataLoader
    {
        return $this->schemaDefinitionReferenceTypeDataLoader ??= $this->instanceManager->getInstance(SchemaDefinitionReferenceTypeDataLoader::class);
    }

    /**
     * Prepending with only 1 "_" instead of 2 "__" to avoid error in graphql-js
     *
     * @see https://github.com/graphql-java/graphql-java/pull/2221#issuecomment-808044041
     */
    public function getTypeName(): string
    {
        return '_DirectiveExtensions';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Extensions (custom metadata) added to the directive', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var DirectiveExtensions */
        $directiveExtensions = $object;
        return $directiveExtensions->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSchemaDefinitionReferenceTypeDataLoader();
    }
}
