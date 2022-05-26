<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractArrayValuesToQueryFilterInput;

class DatesFilterInput extends AbstractArrayValuesToQueryFilterInput
{
    /**
     * @return array<int|string,string>
     */
    protected function getValueToQueryArgKeys(): array
    {
        return [
            'from' => 'date-from',
            'to' => 'date-to',
        ];
    }

    protected function avoidSettingArrayValueIfEmpty(): bool
    {
        return true;
    }
}
