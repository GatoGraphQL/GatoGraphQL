<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByArrayValueOperatorEnumTypeResolver;

class MetaQueryCompareByArrayValueInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver = null;
    private ?MetaQueryCompareByArrayValueOperatorEnumTypeResolver $metaQueryCompareByArrayValueOperatorEnumTypeResolver = null;

    final public function setAnyBuiltInScalarScalarTypeResolver(AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver): void
    {
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
    }
    final protected function getAnyBuiltInScalarScalarTypeResolver(): AnyBuiltInScalarScalarTypeResolver
    {
        /** @var AnyBuiltInScalarScalarTypeResolver */
        return $this->anyBuiltInScalarScalarTypeResolver ??= $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
    }
    final public function setMetaQueryCompareByArrayValueOperatorEnumTypeResolver(MetaQueryCompareByArrayValueOperatorEnumTypeResolver $metaQueryCompareByArrayValueOperatorEnumTypeResolver): void
    {
        $this->metaQueryCompareByArrayValueOperatorEnumTypeResolver = $metaQueryCompareByArrayValueOperatorEnumTypeResolver;
    }
    final protected function getMetaQueryCompareByArrayValueOperatorEnumTypeResolver(): MetaQueryCompareByArrayValueOperatorEnumTypeResolver
    {
        /** @var MetaQueryCompareByArrayValueOperatorEnumTypeResolver */
        return $this->metaQueryCompareByArrayValueOperatorEnumTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByArrayValueOperatorEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByArrayValueInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'value' => $this->getAnyBuiltInScalarScalarTypeResolver(),
            'operator' => $this->getMetaQueryCompareByArrayValueOperatorEnumTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'value' => $this->__('Custom field value', 'meta'),
            'operator' => $this->__('The operator to compare against', 'meta'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'operator' => MetaQueryCompareByOperators::IN,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'operator' => SchemaTypeModifiers::MANDATORY,
            'value' => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
