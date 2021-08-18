<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_AUTHOR_IDS = 'filterinput-author-ids';
    public const FILTERINPUT_AUTHOR_SLUG = 'filterinput-author-slug';
    public const FILTERINPUT_EXCLUDE_AUTHOR_IDS = 'filterinput-exclude-author-ids';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_AUTHOR_IDS],
            [self::class, self::FILTERINPUT_AUTHOR_SLUG],
            [self::class, self::FILTERINPUT_EXCLUDE_AUTHOR_IDS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_AUTHOR_IDS:
                $query['author-ids'] = $value;
                break;
            case self::FILTERINPUT_AUTHOR_SLUG:
                $query['author-slug'] = $value;
                break;
            case self::FILTERINPUT_EXCLUDE_AUTHOR_IDS:
                $query['exclude-author-ids'] = $value;
                break;
        }
    }
}
