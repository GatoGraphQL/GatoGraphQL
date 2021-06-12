<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_ORDER = 'filterinput-order';
    public const FILTERINPUT_LIMIT = 'filterinput-limit';
    public const FILTERINPUT_OFFSET = 'filterinput-offset';
    public const FILTERINPUT_SEARCH = 'filterinput-search';
    public const FILTERINPUT_DATES = 'filterinput-dates';
    public const FILTERINPUT_INCLUDE = 'filterinput-include';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_ORDER],
            [self::class, self::FILTERINPUT_LIMIT],
            [self::class, self::FILTERINPUT_OFFSET],
            [self::class, self::FILTERINPUT_SEARCH],
            [self::class, self::FILTERINPUT_DATES],
            [self::class, self::FILTERINPUT_INCLUDE],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_ORDER:
                $query['orderby'] = $value['orderby'];
                $query['order'] = $value['order'];
                break;
            case self::FILTERINPUT_LIMIT:
                $query['limit'] = $value;
                break;
            case self::FILTERINPUT_OFFSET:
                $query['offset'] = $value;
                break;
            case self::FILTERINPUT_SEARCH:
                $query['search'] = $value;
                break;
            case self::FILTERINPUT_DATES:
                $query['date-from'] = $value['from'];
                $query['date-to'] = $value['to'];
                break;
            case self::FILTERINPUT_INCLUDE:
                $query['include'] = $value;
                break;
        }
    }
}



