<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;

class MetaQueryCompareByOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?MetaQueryCompareByKeyInputObjectTypeResolver $metaQueryCompareByKeyInputObjectTypeResolver = null;
    private ?MetaQueryCompareBySingleValueInputObjectTypeResolver $metaQueryCompareBySingleValueInputObjectTypeResolver = null;
    private ?MetaQueryCompareByArrayValueInputObjectTypeResolver $metaQueryCompareByArrayValueInputObjectTypeResolver = null;

    final public function setMetaQueryCompareByKeyInputObjectTypeResolver(MetaQueryCompareByKeyInputObjectTypeResolver $metaQueryCompareByKeyInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByKeyInputObjectTypeResolver = $metaQueryCompareByKeyInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByKeyInputObjectTypeResolver(): MetaQueryCompareByKeyInputObjectTypeResolver
    {
        return $this->metaQueryCompareByKeyInputObjectTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByKeyInputObjectTypeResolver::class);
    }
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
            'key' => $this->getMetaQueryCompareByKeyInputObjectTypeResolver(),
            'singleValue' => $this->getMetaQueryCompareBySingleValueInputObjectTypeResolver(),
            'arrayValue' => $this->getMetaQueryCompareByArrayValueInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'key' => $this->getTranslationAPI()->__('Compare against the meta key', 'meta'),
            'singleValue' => $this->getTranslationAPI()->__('Compare against a single meta value', 'meta'),
            'arrayValue' => $this->getTranslationAPI()->__('Compare against an array meta value', 'meta'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
