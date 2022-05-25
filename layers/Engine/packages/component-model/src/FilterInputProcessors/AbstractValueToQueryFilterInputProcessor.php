<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

abstract class AbstractValueToQueryFilterInputProcessor extends AbstractFilterInputProcessor
{
    final public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        $query[$this->getQueryArgKey($filterInput)] = $value;
    }

    abstract protected function getQueryArgKey(array $filterInput): string;
}
