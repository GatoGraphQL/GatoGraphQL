<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;

class MetaQueryCompareByOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?MetaQueryCompareByKeyInputObjectTypeResolver $metaQueryCompareByKeyInputObjectTypeResolver = null;
    private ?MetaQueryCompareByNumericValueInputObjectTypeResolver $metaQueryCompareByNumericValueInputObjectTypeResolver = null;
    private ?MetaQueryCompareByStringValueInputObjectTypeResolver $metaQueryCompareByStringValueInputObjectTypeResolver = null;
    private ?MetaQueryCompareByArrayValueInputObjectTypeResolver $metaQueryCompareByArrayValueInputObjectTypeResolver = null;

    final protected function getMetaQueryCompareByKeyInputObjectTypeResolver(): MetaQueryCompareByKeyInputObjectTypeResolver
    {
        if ($this->metaQueryCompareByKeyInputObjectTypeResolver === null) {
            /** @var MetaQueryCompareByKeyInputObjectTypeResolver */
            $metaQueryCompareByKeyInputObjectTypeResolver = $this->instanceManager->getInstance(MetaQueryCompareByKeyInputObjectTypeResolver::class);
            $this->metaQueryCompareByKeyInputObjectTypeResolver = $metaQueryCompareByKeyInputObjectTypeResolver;
        }
        return $this->metaQueryCompareByKeyInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByNumericValueInputObjectTypeResolver(): MetaQueryCompareByNumericValueInputObjectTypeResolver
    {
        if ($this->metaQueryCompareByNumericValueInputObjectTypeResolver === null) {
            /** @var MetaQueryCompareByNumericValueInputObjectTypeResolver */
            $metaQueryCompareByNumericValueInputObjectTypeResolver = $this->instanceManager->getInstance(MetaQueryCompareByNumericValueInputObjectTypeResolver::class);
            $this->metaQueryCompareByNumericValueInputObjectTypeResolver = $metaQueryCompareByNumericValueInputObjectTypeResolver;
        }
        return $this->metaQueryCompareByNumericValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByStringValueInputObjectTypeResolver(): MetaQueryCompareByStringValueInputObjectTypeResolver
    {
        if ($this->metaQueryCompareByStringValueInputObjectTypeResolver === null) {
            /** @var MetaQueryCompareByStringValueInputObjectTypeResolver */
            $metaQueryCompareByStringValueInputObjectTypeResolver = $this->instanceManager->getInstance(MetaQueryCompareByStringValueInputObjectTypeResolver::class);
            $this->metaQueryCompareByStringValueInputObjectTypeResolver = $metaQueryCompareByStringValueInputObjectTypeResolver;
        }
        return $this->metaQueryCompareByStringValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByArrayValueInputObjectTypeResolver(): MetaQueryCompareByArrayValueInputObjectTypeResolver
    {
        if ($this->metaQueryCompareByArrayValueInputObjectTypeResolver === null) {
            /** @var MetaQueryCompareByArrayValueInputObjectTypeResolver */
            $metaQueryCompareByArrayValueInputObjectTypeResolver = $this->instanceManager->getInstance(MetaQueryCompareByArrayValueInputObjectTypeResolver::class);
            $this->metaQueryCompareByArrayValueInputObjectTypeResolver = $metaQueryCompareByArrayValueInputObjectTypeResolver;
        }
        return $this->metaQueryCompareByArrayValueInputObjectTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByInput';
    }

    protected function isOneInputValueMandatory(): bool
    {
        return false;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'key' => $this->getMetaQueryCompareByKeyInputObjectTypeResolver(),
            'numericValue' => $this->getMetaQueryCompareByNumericValueInputObjectTypeResolver(),
            'stringValue' => $this->getMetaQueryCompareByStringValueInputObjectTypeResolver(),
            'arrayValue' => $this->getMetaQueryCompareByArrayValueInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'key' => $this->__('Compare against the meta key', 'meta'),
            'numericValue' => $this->__('Compare against a numeric meta value', 'meta'),
            'stringValue' => $this->__('Compare against a string meta value', 'meta'),
            'arrayValue' => $this->__('Compare against an array meta value', 'meta'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
