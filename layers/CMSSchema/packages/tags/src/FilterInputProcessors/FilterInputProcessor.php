<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_TAG_SLUGS = 'filterinput-tag-slugs';
    public final const FILTERINPUT_TAG_IDS = 'filterinput-tag-ids';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_TAG_SLUGS],
            [self::class, self::FILTERINPUT_TAG_IDS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_TAG_SLUGS:
                $query['tag-slugs'] = $value;
                break;
            case self::FILTERINPUT_TAG_IDS:
                $query['tag-ids'] = $value;
                break;
        }
    }
}
