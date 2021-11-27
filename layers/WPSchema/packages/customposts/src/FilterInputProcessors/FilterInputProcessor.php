<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_HAS_PASSWORD = 'filterinput-has-password';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_HAS_PASSWORD],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_HAS_PASSWORD:
                $query['has-password'] = $value;
                break;
        }
    }
}
