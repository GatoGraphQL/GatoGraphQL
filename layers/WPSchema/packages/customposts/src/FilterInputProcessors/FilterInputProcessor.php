<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_HAS_PASSWORD = 'filterinput-has-password';
    public final const FILTERINPUT_PASSWORD = 'filterinput-password';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_HAS_PASSWORD],
            [self::class, self::FILTERINPUT_PASSWORD],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_HAS_PASSWORD:
                $query['has-password'] = $value;
                break;
            case self::FILTERINPUT_PASSWORD:
                $query['password'] = $value;
                break;
        }
    }
}
