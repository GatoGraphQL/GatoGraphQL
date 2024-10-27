<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
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

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getOrderEnumTypeResolver(): OrderEnumTypeResolver
    {
        if ($this->orderEnumTypeResolver === null) {
            /** @var OrderEnumTypeResolver */
            $orderEnumTypeResolver = $this->instanceManager->getInstance(OrderEnumTypeResolver::class);
            $this->orderEnumTypeResolver = $orderEnumTypeResolver;
        }
        return $this->orderEnumTypeResolver;
    }
    final protected function getOrderByFilterInput(): OrderByFilterInput
    {
        if ($this->excludeIDsFilterInput === null) {
            /** @var OrderByFilterInput */
            $excludeIDsFilterInput = $this->instanceManager->getInstance(OrderByFilterInput::class);
            $this->excludeIDsFilterInput = $excludeIDsFilterInput;
        }
        return $this->excludeIDsFilterInput;
    }
    final protected function getOrderFilterInput(): OrderFilterInput
    {
        if ($this->includeFilterInput === null) {
            /** @var OrderFilterInput */
            $includeFilterInput = $this->instanceManager->getInstance(OrderFilterInput::class);
            $this->includeFilterInput = $includeFilterInput;
        }
        return $this->includeFilterInput;
    }

    public function getTypeName(): string
    {
        return 'SortInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to sort custom posts', 'customposts');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
