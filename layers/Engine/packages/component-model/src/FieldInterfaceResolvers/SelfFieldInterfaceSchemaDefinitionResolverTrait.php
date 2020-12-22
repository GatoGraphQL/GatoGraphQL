<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;

trait SelfFieldInterfaceSchemaDefinitionResolverTrait
{
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    /**
     * The object resolves its own schema definition
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @param array<string, mixed> $fieldArgs
     * @return void
     */
    public function getSchemaDefinitionResolver(): ?FieldSchemaDefinitionResolverInterface
    {
        return $this;
    }

    public function getSchemaFieldType(string $fieldName): ?string
    {
        // By default, it can be of any type. Return this instead of null since the type is mandatory for GraphQL, so we avoid its non-implementation by the developer to throw errors
        return SchemaDefinition::TYPE_MIXED;
    }

    public function isSchemaFieldResponseNonNullable(string $fieldName): bool
    {
        // By default, types are nullable
        return false;
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        return null;
    }

    public function getSchemaFieldArgs(string $fieldName): array
    {
        return [];
    }

    abstract protected function hasSchemaFieldVersion(string $fieldName): bool;

    public function getFilteredSchemaFieldArgs(string $fieldName): array
    {
        $schemaFieldArgs = $this->getSchemaFieldArgs($fieldName);
        /**
         * Add the "versionConstraint" param. Add it at the end, so it doesn't affect the order of params for "orderedSchemaFieldArgs"
         */
        $this->maybeAddVersionConstraintSchemaFieldOrDirectiveArg(
            $schemaFieldArgs,
            $this->hasSchemaFieldVersion($fieldName)
        );
        return $schemaFieldArgs;
    }

    public function getSchemaFieldDeprecationDescription(string $fieldName, array $fieldArgs = []): ?string
    {
        return null;
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, string $fieldName): void
    {
    }
}
