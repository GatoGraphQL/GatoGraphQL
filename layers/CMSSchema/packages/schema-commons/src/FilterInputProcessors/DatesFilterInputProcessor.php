<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractArrayValuesToQueryFilterInputProcessor;

class DatesFilterInputProcessor extends AbstractArrayValuesToQueryFilterInputProcessor
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
