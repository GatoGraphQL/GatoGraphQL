<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class FilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
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

    protected function getQueryArgKey(array $filterInput): string
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
