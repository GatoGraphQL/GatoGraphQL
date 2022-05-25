<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractArrayValuesToQueryFilterInputProcessor;

class SortFilterInputProcessor extends AbstractArrayValuesToQueryFilterInputProcessor
{
    /**
     * @return string[]
     */
    protected function getQueryArgKeys(array $filterInput): array
    {
        return ['orderby', 'order'];
    }

    protected function avoidSettingArrayValueIfEmpty(): bool
    {
        return true;
    }
}
