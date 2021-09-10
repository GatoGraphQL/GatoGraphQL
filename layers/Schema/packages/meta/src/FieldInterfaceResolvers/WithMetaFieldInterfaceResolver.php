<?php

declare(strict_types=1);

namespace PoPSchema\Meta\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\Meta\TypeResolvers\Interface\WithMetaInterfaceTypeResolver;

class WithMetaFieldInterfaceResolver extends AbstractInterfaceTypeFieldResolver
{
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            WithMetaInterfaceTypeResolver::class,
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'metaValue',
            'metaValues',
        ];
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $types = [
            'metaValue' => SchemaDefinition::TYPE_ANY_SCALAR,
            'metaValues' => SchemaDefinition::TYPE_ANY_SCALAR,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        return match ($fieldName) {
            'metaValues' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($fieldName),
        };
    }

    public function getSchemaFieldArgs(string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($fieldName);
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'key',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The meta key', 'meta'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'metaValue' => $this->translationAPI->__('Single meta value', 'custompostmeta'),
            'metaValues' => $this->translationAPI->__('List of meta values', 'custompostmeta'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }
}
