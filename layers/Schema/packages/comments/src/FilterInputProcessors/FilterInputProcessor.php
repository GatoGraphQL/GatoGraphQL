<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_CUSTOMPOST_IDS = 'filterinput-custompost-ids';
    public const FILTERINPUT_CUSTOMPOST_ID = 'filterinput-custompost-id';
    public const FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS = 'filterinput-exclude-custompost-ids';
    public const FILTERINPUT_COMMENT_TYPES = 'filterinput-comment-types';
    public const FILTERINPUT_COMMENT_STATUS = 'filterinput-comment-status';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_CUSTOMPOST_IDS],
            [self::class, self::FILTERINPUT_CUSTOMPOST_ID],
            [self::class, self::FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
            [self::class, self::FILTERINPUT_COMMENT_TYPES],
            [self::class, self::FILTERINPUT_COMMENT_STATUS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_CUSTOMPOST_IDS:
                $query['customPostIDs'] = $value;
                break;
            case self::FILTERINPUT_CUSTOMPOST_ID:
                $query['customPostID'] = $value;
                break;
            case self::FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
                $query['exclude-customPostIDs'] = $value;
                break;
            case self::FILTERINPUT_COMMENT_TYPES:
                $query['types'] = $value;
                break;
            case self::FILTERINPUT_COMMENT_STATUS:
                $query['status'] = $value;
                break;
        }
    }
}
