<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\OrderByFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\OrderFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\TypeResolvers\EnumType\OrderEnumTypeResolver;
use PoPSchema\SchemaCommons\Constants\Order;

class SortInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?OrderEnumTypeResolver $orderEnumTypeResolver = null;
    private ?OrderByFilterInputProcessor $excludeIDsFilterInputProcessor = null;
    private ?OrderFilterInputProcessor $includeFilterInputProcessor = null;

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
    final public function setOrderByFilterInputProcessor(OrderByFilterInputProcessor $excludeIDsFilterInputProcessor): void
    {
        $this->excludeIDsFilterInputProcessor = $excludeIDsFilterInputProcessor;
    }
    final protected function getOrderByFilterInputProcessor(): OrderByFilterInputProcessor
    {
        return $this->excludeIDsFilterInputProcessor ??= $this->instanceManager->getInstance(OrderByFilterInputProcessor::class);
    }
    final public function setOrderFilterInputProcessor(OrderFilterInputProcessor $includeFilterInputProcessor): void
    {
        $this->includeFilterInputProcessor = $includeFilterInputProcessor;
    }
    final protected function getOrderFilterInputProcessor(): OrderFilterInputProcessor
    {
        return $this->includeFilterInputProcessor ??= $this->instanceManager->getInstance(OrderFilterInputProcessor::class);
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

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'order' => $this->getOrderFilterInputProcessor(),
            'by' => $this->getOrderByFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
