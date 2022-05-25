<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

abstract class AbstractValueToQueryFilterInputProcessor extends AbstractFilterInputProcessor
{
    final public function filterDataloadQueryArgs(array &$query, mixed $value): void
    {
        $value = $this->getValue($value);
        if ($this->avoidSettingValueIfEmpty() && empty($value)) {
            return;
        }
        $query[$this->getQueryArgKey()] = $value;
    }

    abstract protected function getQueryArgKey(): string;
    
    protected function getValue(mixed $value): mixed
    {
        return $value;
    }

    protected function avoidSettingValueIfEmpty(): bool
    {
        return false;
    }
}
