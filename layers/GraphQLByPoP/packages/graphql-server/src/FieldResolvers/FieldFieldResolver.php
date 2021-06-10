<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\FieldTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;

class FieldFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(FieldTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'description',
            'args',
            'type',
            'isDeprecated',
            'deprecationReason',
            'extensions',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'name' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'args' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'type' => SchemaDefinition::TYPE_STRING,
            'isDeprecated' => SchemaDefinition::TYPE_BOOL,
            'deprecationReason' => SchemaDefinition::TYPE_STRING,
            'extensions' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_MIXED),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'name',
            'args',
            'type',
            'isDeprecated',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'name' => $this->translationAPI->__('Field\'s name', 'graphql-server'),
            'description' => $this->translationAPI->__('Field\'s description', 'graphql-server'),
            'args' => $this->translationAPI->__('Field arguments', 'graphql-server'),
            'type' => $this->translationAPI->__('Type to which the field belongs', 'graphql-server'),
            'isDeprecated' => $this->translationAPI->__('Is the field deprecated?', 'graphql-server'),
            'deprecationReason' => $this->translationAPI->__('Why was the field deprecated?', 'graphql-server'),
            'extensions' => $this->translationAPI->__('Custom metadata added to the field (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
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
        $field = $resultItem;
        switch ($fieldName) {
            case 'name':
                return $field->getName();
            case 'description':
                return $field->getDescription();
            case 'args':
                return $field->getArgIDs();
            case 'type':
                return $field->getTypeID();
            case 'isDeprecated':
                return $field->isDeprecated();
            case 'deprecationReason':
                return $field->getDeprecationDescription();
            case 'extensions':
                return $field->getExtensions();
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'args':
                return InputValueTypeResolver::class;
            case 'type':
                return TypeTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
