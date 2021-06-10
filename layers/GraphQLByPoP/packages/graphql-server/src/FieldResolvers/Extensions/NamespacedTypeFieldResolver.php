<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\Extensions;

use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\EnumTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class NamespacedTypeFieldResolver extends AbstractDBDataFieldResolver
{
    use EnumTypeFieldSchemaDefinitionResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(TypeTypeResolver::class);
    }

    public function getPriorityToAttachToClasses(): int
    {
        // Higher priority => Process first
        return 100;
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
        ];
    }

    /**
     * Only use this fieldResolver when parameter `namespaced` is provided. Otherwise, use the default implementation
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        return $fieldName == 'name' && isset($fieldArgs['namespaced']);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'name' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'name',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'name' => $this->translationAPI->__('Type\'s name as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACvBBCyBH6rd), indicating if the type must be namespaced or not', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'name':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'namespaced',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Namespace type name?', 'graphql-server'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $type = $resultItem;
        switch ($fieldName) {
            case 'name':
                if ($fieldArgs['namespaced']) {
                    return $type->getNamespacedName();
                }
                return $type->getElementName();
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
