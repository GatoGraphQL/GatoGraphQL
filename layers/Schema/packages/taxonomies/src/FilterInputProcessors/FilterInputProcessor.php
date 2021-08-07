<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_SLUGS = 'filterinput-slugs';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_SLUGS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_SLUGS:
                $query['slugs'] = $value;
                break;
        }
    }
}
