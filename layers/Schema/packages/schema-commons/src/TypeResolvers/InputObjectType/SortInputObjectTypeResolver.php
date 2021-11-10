<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\EnumType\OrderEnumTypeResolver;

class SortInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?OrderEnumTypeResolver $orderEnumTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setOrderEnumTypeResolver(OrderEnumTypeResolver $orderEnumTypeResolver): void
    {
        $this->orderEnumTypeResolver = $orderEnumTypeResolver;
    }
    final protected function getOrderEnumTypeResolver(): OrderEnumTypeResolver
    {
        return $this->orderEnumTypeResolver ??= $this->instanceManager->getInstance(OrderEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'SortInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'order' => $this->getOrderEnumTypeResolver(),
            'by' => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'order' => $this->getTranslationAPI()->__('Sorting direction', 'schema-commons'),
            'by' => $this->getTranslationAPI()->__('Name of the property to order by', 'schema-commons'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
