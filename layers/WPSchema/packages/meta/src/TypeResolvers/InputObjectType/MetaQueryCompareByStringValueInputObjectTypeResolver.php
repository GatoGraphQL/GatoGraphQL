<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByStringValueOperatorEnumTypeResolver;

class MetaQueryCompareByStringValueInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?MetaQueryCompareByStringValueOperatorEnumTypeResolver $metaQueryCompareByStringValueOperatorEnumTypeResolver = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getMetaQueryCompareByStringValueOperatorEnumTypeResolver(): MetaQueryCompareByStringValueOperatorEnumTypeResolver
    {
        if ($this->metaQueryCompareByStringValueOperatorEnumTypeResolver === null) {
            /** @var MetaQueryCompareByStringValueOperatorEnumTypeResolver */
            $metaQueryCompareByStringValueOperatorEnumTypeResolver = $this->instanceManager->getInstance(MetaQueryCompareByStringValueOperatorEnumTypeResolver::class);
            $this->metaQueryCompareByStringValueOperatorEnumTypeResolver = $metaQueryCompareByStringValueOperatorEnumTypeResolver;
        }
        return $this->metaQueryCompareByStringValueOperatorEnumTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByStringValueInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
            'value' => $this->__('Custom field value', 'meta'),
            'operator' => $this->__('The operator to compare against', 'meta'),
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
