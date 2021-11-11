<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorManagerInterface;
use stdClass;

abstract class AbstractQueryableInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements QueryableInputObjectTypeResolverInterface
{
    /** @var array<string, ?array> */
    private array $consolidatedInputFieldFilterInputCache = [];

    private ?FilterInputProcessorManagerInterface $filterInputProcessorManager = null;

    final public function setFilterInputProcessorManager(FilterInputProcessorManagerInterface $filterInputProcessorManager): void
    {
        $this->filterInputProcessorManager = $filterInputProcessorManager;
    }
    final protected function getFilterInputProcessorManager(): FilterInputProcessorManagerInterface
    {
        return $this->filterInputProcessorManager ??= $this->instanceManager->getInstance(FilterInputProcessorManagerInterface::class);
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?array
    {
        return null;
    }

    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedInputFieldFilterInput(string $inputFieldName): ?array
    {
        if (array_key_exists($inputFieldName, $this->consolidatedInputFieldFilterInputCache)) {
            return $this->consolidatedInputFieldFilterInputCache[$inputFieldName];
        }
        $this->consolidatedInputFieldFilterInputCache[$inputFieldName] = $this->getHooksAPI()->applyFilters(
            HookNames::INPUT_FIELD_FILTER_INPUT,
            $this->getInputFieldFilterInput($inputFieldName),
            $this,
            $inputFieldName,
        );
        return $this->consolidatedInputFieldFilterInputCache[$inputFieldName];
    }

    /**
     * @param array<string, mixed> $query
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass $inputValue): void
    {
        foreach ((array)$inputValue as $inputFieldName => $inputFieldValue) {
            $this->integrateInputFieldValueToFilteringQueryArgs($inputFieldName, $query, $inputFieldValue);
        }
    }
    /**
     * @param array<string, mixed> $query
     */
    protected function integrateInputFieldValueToFilteringQueryArgs(string $inputFieldName, array &$query, mixed $inputFieldValue): void
    {
        /**
         * If the input field defines a FilterInput, apply it to obtain the filtering query
         */
        if ($filterInput = $this->getConsolidatedInputFieldFilterInput($inputFieldName)) {
            /** @var FilterInputProcessorInterface */
            $filterInputProcessor = $this->getFilterInputProcessorManager()->getProcessor($filterInput);
            $filterInputProcessor->filterDataloadQueryArgs($filterInput, $query, $inputFieldValue);
            return;
        }

        $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();
        $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName];
        $isQueryableInputObjectTypeResolver = $inputFieldTypeResolver instanceof QueryableInputObjectTypeResolverInterface;

        /**
         * Check if to copy the value directly to the filtering query args
         */
        if ($queryArgName = $this->getFilteringQueryArgNameToCopyInputFieldValue($inputFieldName)) {
            /**
             * If this input field is an InputObject, then copy as an array under the specified entry
             */
            if ($isQueryableInputObjectTypeResolver) {
                $query[$queryArgName] = [];
                $inputFieldTypeResolver->integrateInputValueToFilteringQueryArgs($query[$queryArgName], $inputFieldValue);
                return;
            }
            /**
             * Copy the value under the specified entry
             */
            $query[$queryArgName] = $inputFieldValue;
            return;
        }

        /**
         * If the input field is an InputObject, recursively apply this function
         */
        if ($isQueryableInputObjectTypeResolver) {
            $inputFieldTypeResolver->integrateInputValueToFilteringQueryArgs($query, $inputFieldValue);
        }
    }
    protected function getFilteringQueryArgNameToCopyInputFieldValue(string $inputFieldName): ?string
    {
        return null;
    }
}
