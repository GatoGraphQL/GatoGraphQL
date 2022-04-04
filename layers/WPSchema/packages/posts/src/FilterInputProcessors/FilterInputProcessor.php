<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_IS_STICKY = 'filterinput-is-sticky';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_IS_STICKY],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_IS_STICKY:
                $query['is-sticky'] = $value;
                break;
        }
    }
}
