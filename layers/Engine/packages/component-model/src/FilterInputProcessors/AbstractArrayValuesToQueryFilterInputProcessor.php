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
        foreach ($this->getQueryArgKeys($filterInput) as $key) {
            if ($avoidSettingArrayValueIfEmpty && empty($value[$key] ?? null)) {
                continue;
            }
            $query[$key] = $value[$key] ?? null;
        }
    }

    /**
     * @return string[]
     */
    abstract protected function getQueryArgKeys(array $filterInput): array;
    
    protected function getValue(array $value): array
    {
        return $value;
    }

    protected function avoidSettingArrayValueIfEmpty(): bool
    {
        return false;
    }
}
