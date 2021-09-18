<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver;

class SchemaObjectTypeResolver extends AbstractIntrospectionObjectTypeResolver
{
    /**
     * Can't inject in constructor because of a circular reference
     */
    protected ?SchemaTypeDataLoader $schemaTypeDataLoader = null;

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
        if ($this->schemaTypeDataLoader === null) {
            $this->schemaTypeDataLoader = $this->instanceManager->getInstance(SchemaTypeDataLoader::class);
        }
        return $this->schemaTypeDataLoader;
    }
}
