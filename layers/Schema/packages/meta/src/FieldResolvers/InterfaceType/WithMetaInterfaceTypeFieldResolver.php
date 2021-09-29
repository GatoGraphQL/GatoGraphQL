<?php

declare(strict_types=1);

namespace PoPSchema\Meta\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyScalarScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Meta\TypeResolvers\InterfaceType\WithMetaInterfaceTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class WithMetaInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    protected AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    public function autowireWithMetaInterfaceTypeFieldResolver(
        AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->anyScalarScalarTypeResolver = $anyScalarScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

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

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'metaValue' => $this->anyScalarScalarTypeResolver,
            'metaValues' => $this->anyScalarScalarTypeResolver,
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        return match ($fieldName) {
            'metaValues' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($fieldName),
        };
    }

    public function getSchemaFieldArgNameResolvers(string $fieldName): array
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgNameResolvers($fieldName),
        };
    }
    
    public function getSchemaFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgDescription($fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgDefaultValue($fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgTypeModifiers(string $fieldName, string $fieldArgName): ?int
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgTypeModifiers($fieldName, $fieldArgName),
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
        return match ($fieldName) {
            'metaValue' => $this->translationAPI->__('Single meta value', 'custompostmeta'),
            'metaValues' => $this->translationAPI->__('List of meta values', 'custompostmeta'),
            default => parent::getSchemaFieldDescription($fieldName),
        };
    }
}
