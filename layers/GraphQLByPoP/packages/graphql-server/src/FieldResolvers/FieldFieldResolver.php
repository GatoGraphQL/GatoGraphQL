<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use GraphQLByPoP\GraphQLServer\TypeResolvers\FieldTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

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
            'args' => SchemaDefinition::TYPE_ID,
            'type' => SchemaDefinition::TYPE_STRING,
            'isDeprecated' => SchemaDefinition::TYPE_BOOL,
            'deprecationReason' => SchemaDefinition::TYPE_STRING,
            'extensions' => SchemaDefinition::TYPE_MIXED,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'name',
            'type',
            'isDeprecated'
                => SchemaTypeModifiers::NON_NULLABLE,
            'extensions'
                => SchemaTypeModifiers::IS_ARRAY,
            'args'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
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
