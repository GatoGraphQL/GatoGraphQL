<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\OrderByFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\OrderFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\EnumType\OrderEnumTypeResolver;
use PoPSchema\SchemaCommons\Constants\Order;

class SortInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?OrderEnumTypeResolver $orderEnumTypeResolver = null;
    private ?OrderByFilterInput $excludeIDsFilterInput = null;
    private ?OrderFilterInput $includeFilterInput = null;

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
    final public function setOrderByFilterInput(OrderByFilterInput $excludeIDsFilterInput): void
    {
        $this->excludeIDsFilterInput = $excludeIDsFilterInput;
    }
    final protected function getOrderByFilterInput(): OrderByFilterInput
    {
        return $this->excludeIDsFilterInput ??= $this->instanceManager->getInstance(OrderByFilterInput::class);
    }
    final public function setOrderFilterInput(OrderFilterInput $includeFilterInput): void
    {
        $this->includeFilterInput = $includeFilterInput;
    }
    final protected function getOrderFilterInput(): OrderFilterInput
    {
        return $this->includeFilterInput ??= $this->instanceManager->getInstance(OrderFilterInput::class);
    }

    public function getTypeName(): string
    {
        return 'SortInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to sort custom posts', 'customposts');
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
            'order' => $this->__('Sorting direction', 'schema-commons'),
            'by' => $this->__('Property to order by', 'schema-commons'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'order' => Order::DESC,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'order' => $this->getOrderFilterInput(),
            'by' => $this->getOrderByFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
