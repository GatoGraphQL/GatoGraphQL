<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

trait SelfFieldSchemaDefinitionResolverTrait
{
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    /**
     * The object resolves its own schema definition
     */
    public function getSchemaDefinitionResolver(ObjectTypeResolverInterface $typeResolver): ?FieldSchemaDefinitionResolverInterface
    {
        return $this;
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $typeResolver, string $fieldName): string
    {
        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        return $schemaDefinitionService->getDefaultType();
    }

    /**
     * By default types are nullable, and not an array
     */
    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return null;
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return null;
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $typeResolver, string $fieldName): array
    {
        return [];
    }

    public function getSchemaFieldDeprecationDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        return null;
    }

    public function validateFieldArgument(
        ObjectTypeResolverInterface $typeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        return [];
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, ObjectTypeResolverInterface $typeResolver, string $fieldName): void
    {
    }
}
