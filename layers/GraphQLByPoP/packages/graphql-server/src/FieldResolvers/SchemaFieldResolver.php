<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use GraphQLByPoP\GraphQLServer\TypeResolvers\DirectiveTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class SchemaFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(SchemaTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'queryType',
            'mutationType',
            'subscriptionType',
            'types',
            'directives',
            'type',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'queryType' => SchemaDefinition::TYPE_ID,
            'mutationType' => SchemaDefinition::TYPE_ID,
            'subscriptionType' => SchemaDefinition::TYPE_ID,
            'types' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'directives' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'type' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'queryType',
            'types',
            'directives',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'queryType' => $this->translationAPI->__('The type, accessible from the root, that resolves queries', 'graphql-server'),
            'mutationType' => $this->translationAPI->__('The type, accessible from the root, that resolves mutations', 'graphql-server'),
            'subscriptionType' => $this->translationAPI->__('The type, accessible from the root, that resolves subscriptions', 'graphql-server'),
            'types' => $this->translationAPI->__('All types registered in the data graph', 'graphql-server'),
            'directives' => $this->translationAPI->__('All directives registered in the data graph', 'graphql-server'),
            'type' => $this->translationAPI->__('Obtain a specific type from the schema', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'type':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'name',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The name of the type', 'graphql-server'),
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
        $schema = $resultItem;
        switch ($fieldName) {
            case 'queryType':
                return $schema->getQueryTypeID();
            case 'mutationType':
                return $schema->getMutationTypeID();
            case 'subscriptionType':
                return $schema->getSubscriptionTypeID();
            case 'types':
                return $schema->getTypeIDs();
            case 'directives':
                return $schema->getDirectiveIDs();
            case 'type':
                return $schema->getTypeID($fieldArgs['name']);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'queryType':
            case 'mutationType':
            case 'subscriptionType':
            case 'types':
            case 'type':
                return TypeTypeResolver::class;
            case 'directives':
                return DirectiveTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
