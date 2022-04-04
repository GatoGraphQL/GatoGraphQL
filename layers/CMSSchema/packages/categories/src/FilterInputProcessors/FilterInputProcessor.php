<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_CATEGORY_IDS = 'filterinput-category-ids';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_CATEGORY_IDS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_CATEGORY_IDS:
                $query['category-ids'] = $value;
                break;
        }
    }
}
