<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByKeyOperatorEnumTypeResolver;

class MetaQueryCompareByKeyInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?MetaQueryCompareByKeyOperatorEnumTypeResolver $metaQueryCompareByKeyOperatorEnumTypeResolver = null;

    final public function setMetaQueryCompareByKeyOperatorEnumTypeResolver(MetaQueryCompareByKeyOperatorEnumTypeResolver $metaQueryCompareByKeyOperatorEnumTypeResolver): void
    {
        $this->metaQueryCompareByKeyOperatorEnumTypeResolver = $metaQueryCompareByKeyOperatorEnumTypeResolver;
    }
    final protected function getMetaQueryCompareByKeyOperatorEnumTypeResolver(): MetaQueryCompareByKeyOperatorEnumTypeResolver
    {
        if ($this->metaQueryCompareByKeyOperatorEnumTypeResolver === null) {
            /** @var MetaQueryCompareByKeyOperatorEnumTypeResolver */
            $metaQueryCompareByKeyOperatorEnumTypeResolver = $this->instanceManager->getInstance(MetaQueryCompareByKeyOperatorEnumTypeResolver::class);
            $this->metaQueryCompareByKeyOperatorEnumTypeResolver = $metaQueryCompareByKeyOperatorEnumTypeResolver;
        }
        return $this->metaQueryCompareByKeyOperatorEnumTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByKeyInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'operator' => $this->getMetaQueryCompareByKeyOperatorEnumTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'operator' => $this->__('The operator to compare against', 'meta'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'operator' => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'operator' => MetaQueryCompareByOperators::EXISTS,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
