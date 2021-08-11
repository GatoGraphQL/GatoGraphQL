<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_AUTHOR_IDS = 'filterinput-author-ids';
    public const FILTERINPUT_AUTHOR_SLUG = 'filterinput-author-slug';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_AUTHOR_IDS],
            [self::class, self::FILTERINPUT_AUTHOR_SLUG],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_AUTHOR_IDS:
                $query['author-ids'] = $value;
                break;
            case self::FILTERINPUT_AUTHOR_SLUG:
                $query['author-slug'] = $value;
                break;
        }
    }
}
