<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractGlobalObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    public function getFieldNamesToResolve(): array
    {
        return [
            '__typename',
        ];
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            '__typename' => StringScalarTypeResolver::class,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            '__typename',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            '__typename' => $this->translationAPI->__('The object\'s type', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case '__typename':
                return $objectTypeResolver->getMaybeNamespacedTypeName();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
