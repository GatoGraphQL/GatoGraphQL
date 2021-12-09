<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByStringValueOperatorEnumTypeResolver;

class MetaQueryCompareByStringValueInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?MetaQueryCompareByStringValueOperatorEnumTypeResolver $metaQueryCompareByStringValueOperatorEnumTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setMetaQueryCompareByStringValueOperatorEnumTypeResolver(MetaQueryCompareByStringValueOperatorEnumTypeResolver $metaQueryCompareByStringValueOperatorEnumTypeResolver): void
    {
        $this->metaQueryCompareByStringValueOperatorEnumTypeResolver = $metaQueryCompareByStringValueOperatorEnumTypeResolver;
    }
    final protected function getMetaQueryCompareByStringValueOperatorEnumTypeResolver(): MetaQueryCompareByStringValueOperatorEnumTypeResolver
    {
        return $this->metaQueryCompareByStringValueOperatorEnumTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByStringValueOperatorEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByStringValueInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'value' => $this->getStringScalarTypeResolver(),
            'operator' => $this->getMetaQueryCompareByStringValueOperatorEnumTypeResolver(),
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
