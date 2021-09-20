<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\UseObjectDictionaryTypeDataLoaderTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;

class SchemaTypeDataLoader extends AbstractObjectTypeDataLoader
{
    use UseObjectDictionaryTypeDataLoaderTrait;

    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        protected SchemaObjectTypeResolver $schemaObjectTypeResolver,
        protected SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
        );
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->schemaObjectTypeResolver;
    }

    protected function getObjectTypeNewInstance(int | string $id): mixed
    {
        $schemaDefinition = $this->getSchemaDefinition($id);
        if ($schemaDefinition === null) {
            return null;
        }
        return new Schema(
            $schemaDefinition,
            (string) $id
        );
    }

    protected function &getSchemaDefinition(string $id): ?array
    {
        return $this->schemaDefinitionReferenceRegistry->getFullSchemaDefinition();
    }
}
