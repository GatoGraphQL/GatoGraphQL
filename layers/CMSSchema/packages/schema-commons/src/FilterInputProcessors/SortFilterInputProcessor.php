<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractArrayValuesToQueryFilterInputProcessor;

class SortFilterInputProcessor extends AbstractArrayValuesToQueryFilterInputProcessor
{
    /**
     * @return array<int|string,string>
     */
    protected function getValueToQueryArgKeys(array $filterInput): array
    {
        return ['orderby', 'order'];
    }

    protected function avoidSettingArrayValueIfEmpty(): bool
    {
        return true;
    }
}
