<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractArrayValuesToQueryFilterInput;

class SortFilterInput extends AbstractArrayValuesToQueryFilterInput
{
    /**
     * @return array<int|string,string>
     */
    protected function getValueToQueryArgKeys(): array
    {
        return ['orderby', 'order'];
    }

    protected function avoidSettingArrayValueIfEmpty(): bool
    {
        return true;
    }
}
