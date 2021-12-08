<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByNumericValueOperatorEnumTypeResolver;

class MetaQueryCompareByNumericValueInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver = null;
    private ?MetaQueryCompareByNumericValueOperatorEnumTypeResolver $metaQueryCompareByNumericValueOperatorEnumTypeResolver = null;

    final public function setAnyBuiltInScalarScalarTypeResolver(AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver): void
    {
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
    }
    final protected function getAnyBuiltInScalarScalarTypeResolver(): AnyBuiltInScalarScalarTypeResolver
    {
        return $this->anyBuiltInScalarScalarTypeResolver ??= $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
    }
    final public function setMetaQueryCompareByNumericValueOperatorEnumTypeResolver(MetaQueryCompareByNumericValueOperatorEnumTypeResolver $metaQueryCompareByNumericValueOperatorEnumTypeResolver): void
    {
        $this->metaQueryCompareByNumericValueOperatorEnumTypeResolver = $metaQueryCompareByNumericValueOperatorEnumTypeResolver;
    }
    final protected function getMetaQueryCompareByNumericValueOperatorEnumTypeResolver(): MetaQueryCompareByNumericValueOperatorEnumTypeResolver
    {
        return $this->metaQueryCompareByNumericValueOperatorEnumTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByNumericValueOperatorEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByNumericValueInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'value' => $this->getAnyBuiltInScalarScalarTypeResolver(),
            'operator' => $this->getMetaQueryCompareByNumericValueOperatorEnumTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'value' => $this->getTranslationAPI()->__('Custom field value', 'meta'),
            'operator' => $this->getTranslationAPI()->__('The operator to compare against', 'meta'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'operator' => MetaQueryCompareByOperators::EQUALS,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'operator',
            'value'
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
