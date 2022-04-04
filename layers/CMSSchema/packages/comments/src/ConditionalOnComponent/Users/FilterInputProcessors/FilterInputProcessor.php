<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnComponent\Users\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_CUSTOMPOST_AUTHOR_IDS = 'filterinput-custompost-author-ids';
    public final const FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS = 'filterinput-exclude-custompost-author-ids';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_CUSTOMPOST_AUTHOR_IDS],
            [self::class, self::FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_CUSTOMPOST_AUTHOR_IDS:
                $query['custompost-author-ids'] = $value;
                break;
            case self::FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS:
                $query['exclude-custompost-author-ids'] = $value;
                break;
        }
    }
}
