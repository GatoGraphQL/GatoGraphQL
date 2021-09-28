<?php

declare(strict_types=1);

namespace PoPSchema\Meta\FieldResolvers\InterfaceType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyScalarScalarTypeResolver;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Meta\TypeResolvers\InterfaceType\WithMetaInterfaceTypeResolver;

class WithMetaInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    protected AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver;

    #[Required]
    public function autowireWithMetaInterfaceTypeFieldResolver(
        AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver,
    ): void {
        $this->anyScalarScalarTypeResolver = $anyScalarScalarTypeResolver;
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
        $types = [
            'metaValue' => $this->anyScalarScalarTypeResolver,
            'metaValues' => $this->anyScalarScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($fieldName);
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
