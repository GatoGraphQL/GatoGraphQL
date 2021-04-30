<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;

class InputValueFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(InputValueTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'description',
            'type',
            'defaultValue',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'name' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'type' => SchemaDefinition::TYPE_ID,
            'defaultValue' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'name',
            'type',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'name' => $this->translationAPI->__('Input value\'s name as defined by the GraphQL spec', 'graphql-server'),
            'description' => $this->translationAPI->__('Input value\'s description', 'graphql-server'),
            'type' => $this->translationAPI->__('Type of the input value', 'graphql-server'),
            'defaultValue' => $this->translationAPI->__('Default value of the input value', 'graphql-server'),
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
        $inputValue = $resultItem;
        switch ($fieldName) {
            case 'name':
                return $inputValue->getName();
            case 'description':
                return $inputValue->getDescription();
            case 'type':
                return $inputValue->getType()->getID();
            case 'defaultValue':
                return $inputValue->getDefaultValue();
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'type':
                return TypeTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
