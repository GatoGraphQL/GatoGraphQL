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

    final public function setMetaQueryCompareByKeyInputObjectTypeResolver(MetaQueryCompareByKeyInputObjectTypeResolver $metaQueryCompareByKeyInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByKeyInputObjectTypeResolver = $metaQueryCompareByKeyInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByKeyInputObjectTypeResolver(): MetaQueryCompareByKeyInputObjectTypeResolver
    {
        /** @var MetaQueryCompareByKeyInputObjectTypeResolver */
        return $this->metaQueryCompareByKeyInputObjectTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByKeyInputObjectTypeResolver::class);
    }
    final public function setMetaQueryCompareByNumericValueInputObjectTypeResolver(MetaQueryCompareByNumericValueInputObjectTypeResolver $metaQueryCompareByNumericValueInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByNumericValueInputObjectTypeResolver = $metaQueryCompareByNumericValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByNumericValueInputObjectTypeResolver(): MetaQueryCompareByNumericValueInputObjectTypeResolver
    {
        /** @var MetaQueryCompareByNumericValueInputObjectTypeResolver */
        return $this->metaQueryCompareByNumericValueInputObjectTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByNumericValueInputObjectTypeResolver::class);
    }
    final public function setMetaQueryCompareByStringValueInputObjectTypeResolver(MetaQueryCompareByStringValueInputObjectTypeResolver $metaQueryCompareByStringValueInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByStringValueInputObjectTypeResolver = $metaQueryCompareByStringValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByStringValueInputObjectTypeResolver(): MetaQueryCompareByStringValueInputObjectTypeResolver
    {
        /** @var MetaQueryCompareByStringValueInputObjectTypeResolver */
        return $this->metaQueryCompareByStringValueInputObjectTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByStringValueInputObjectTypeResolver::class);
    }
    final public function setMetaQueryCompareByArrayValueInputObjectTypeResolver(MetaQueryCompareByArrayValueInputObjectTypeResolver $metaQueryCompareByArrayValueInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByArrayValueInputObjectTypeResolver = $metaQueryCompareByArrayValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByArrayValueInputObjectTypeResolver(): MetaQueryCompareByArrayValueInputObjectTypeResolver
    {
        /** @var MetaQueryCompareByArrayValueInputObjectTypeResolver */
        return $this->metaQueryCompareByArrayValueInputObjectTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByArrayValueInputObjectTypeResolver::class);
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
