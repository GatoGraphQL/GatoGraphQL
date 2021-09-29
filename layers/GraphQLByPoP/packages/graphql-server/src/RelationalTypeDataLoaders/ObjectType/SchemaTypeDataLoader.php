<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
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

    protected SchemaObjectTypeResolver $schemaObjectTypeResolver;
    protected SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry;

    #[Required]
    public function autowireSchemaTypeDataLoader(
        SchemaObjectTypeResolver $schemaObjectTypeResolver,
        SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry,
    ): void {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
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
