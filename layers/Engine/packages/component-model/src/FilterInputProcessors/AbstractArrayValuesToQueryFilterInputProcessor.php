<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

abstract class AbstractArrayValuesToQueryFilterInputProcessor extends AbstractFilterInputProcessor
{
    final public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        /** @var array $value */
        $value = $this->getValue($value);
        $avoidSettingArrayValueIfEmpty = $this->avoidSettingArrayValueIfEmpty();
        foreach ($this->getValueToQueryArgKeys($filterInput) as $valueKey => $queryKey) {
            if (is_numeric($valueKey)) {
                $valueKey = $queryKey;
            }
            if ($avoidSettingArrayValueIfEmpty && empty($value[$valueKey] ?? null)) {
                continue;
            }
            $query[$queryKey] = $value[$valueKey] ?? null;
        }
    }

    /**
     * @return array<int|string,string>
     */
    abstract protected function getValueToQueryArgKeys(array $filterInput): array;
    
    protected function getValue(array $value): array
    {
        return $value;
    }

    protected function avoidSettingArrayValueIfEmpty(): bool
    {
        return false;
    }
}
