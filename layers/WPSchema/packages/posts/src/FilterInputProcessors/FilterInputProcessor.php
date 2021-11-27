<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_IGNORE_STICKY = 'filterinput-ignore-sticky';
    public const FILTERINPUT_EXCLUDE_STICKY = 'filterinput-exclude-sticky';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_IGNORE_STICKY],
            [self::class, self::FILTERINPUT_EXCLUDE_STICKY],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_IGNORE_STICKY:
                $query['ignore-sticky'] = $value;
                break;
            case self::FILTERINPUT_EXCLUDE_STICKY:
                $query['exclude-sticky'] = $value;
                break;
        }
    }
}
