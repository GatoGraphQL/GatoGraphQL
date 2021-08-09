<?php

declare(strict_types=1);

namespace PoPSchema\Tags\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_TAG_SLUGS = 'filterinput-tag-slugs';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_TAG_SLUGS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_TAG_SLUGS:
                $query['tag-slugs'] = $value;
                break;
        }
    }
}
