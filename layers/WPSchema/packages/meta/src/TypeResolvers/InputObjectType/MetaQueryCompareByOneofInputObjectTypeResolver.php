<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;

class MetaQueryCompareByOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?MetaQueryCompareBySingleValueInputObjectTypeResolver $metaQueryCompareBySingleValueInputObjectTypeResolver = null;
    private ?MetaQueryCompareByArrayValueInputObjectTypeResolver $metaQueryCompareByArrayValueInputObjectTypeResolver = null;

    final public function setMetaQueryCompareBySingleValueInputObjectTypeResolver(MetaQueryCompareBySingleValueInputObjectTypeResolver $metaQueryCompareBySingleValueInputObjectTypeResolver): void
    {
        $this->metaQueryCompareBySingleValueInputObjectTypeResolver = $metaQueryCompareBySingleValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareBySingleValueInputObjectTypeResolver(): MetaQueryCompareBySingleValueInputObjectTypeResolver
    {
        return $this->metaQueryCompareBySingleValueInputObjectTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareBySingleValueInputObjectTypeResolver::class);
    }
    final public function setMetaQueryCompareByArrayValueInputObjectTypeResolver(MetaQueryCompareByArrayValueInputObjectTypeResolver $metaQueryCompareByArrayValueInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByArrayValueInputObjectTypeResolver = $metaQueryCompareByArrayValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByArrayValueInputObjectTypeResolver(): MetaQueryCompareByArrayValueInputObjectTypeResolver
    {
        return $this->metaQueryCompareByArrayValueInputObjectTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByArrayValueInputObjectTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'single' => $this->getMetaQueryCompareBySingleValueInputObjectTypeResolver(),
            'array' => $this->getMetaQueryCompareByArrayValueInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'single' => $this->getTranslationAPI()->__('Compare against a single value', 'meta'),
            'array' => $this->getTranslationAPI()->__('Compare against an array value', 'meta'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
