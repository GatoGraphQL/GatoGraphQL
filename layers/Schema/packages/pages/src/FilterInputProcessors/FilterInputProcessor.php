<?php

declare(strict_types=1);

namespace PoPSchema\Pages\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_PARENT_IDS = 'filterinput-parent-ids';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_PARENT_IDS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_PARENT_IDS:
                $query['parent-ids'] = $value;
                break;
        }
    }
}
