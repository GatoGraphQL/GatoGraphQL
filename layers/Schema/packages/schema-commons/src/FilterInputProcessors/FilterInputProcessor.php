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
    public const FILTERINPUT_EXCLUDE_IDS = 'filterinput-exclude-ids';
    public const FILTERINPUT_PARENT_IDS = 'filterinput-parent-ids';
    public const FILTERINPUT_PARENT_ID = 'filterinput-parent-id';
    public const FILTERINPUT_EXCLUDE_PARENT_IDS = 'filterinput-exclude-parent-ids';
    public const FILTERINPUT_SLUGS = 'filterinput-slugs';
    public const FILTERINPUT_SLUG = 'filterinput-slug';
    public const FILTERINPUT_DATEFORMAT = 'filterinput-date-format';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_ORDER],
            [self::class, self::FILTERINPUT_LIMIT],
            [self::class, self::FILTERINPUT_OFFSET],
            [self::class, self::FILTERINPUT_SEARCH],
            [self::class, self::FILTERINPUT_DATES],
            [self::class, self::FILTERINPUT_INCLUDE],
            [self::class, self::FILTERINPUT_EXCLUDE_IDS],
            [self::class, self::FILTERINPUT_PARENT_IDS],
            [self::class, self::FILTERINPUT_PARENT_ID],
            [self::class, self::FILTERINPUT_EXCLUDE_PARENT_IDS],
            [self::class, self::FILTERINPUT_SLUGS],
            [self::class, self::FILTERINPUT_SLUG],
            [self::class, self::FILTERINPUT_DATEFORMAT],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_ORDER:
                if (isset($value['orderby'])) {
                    $query['orderby'] = $value['orderby'];
                }
                if (isset($value['order'])) {
                    $query['order'] = $value['order'];
                }
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
                if (isset($value['from'])) {
                    $query['date-from'] = $value['from'];
                }
                if (isset($value['to'])) {
                    $query['date-to'] = $value['to'];
                }
                break;
            case self::FILTERINPUT_INCLUDE:
                $query['include'] = $value;
                break;
            case self::FILTERINPUT_EXCLUDE_IDS:
                $query['exclude-ids'] = $value;
                break;
            case self::FILTERINPUT_PARENT_IDS:
                $query['parent-ids'] = $value;
                break;
            case self::FILTERINPUT_PARENT_ID:
                $query['parent-id'] = $value;
                break;
            case self::FILTERINPUT_EXCLUDE_PARENT_IDS:
                $query['exclude-parent-ids'] = $value;
                break;
            case self::FILTERINPUT_SLUGS:
                $query['slugs'] = $value;
                break;
            case self::FILTERINPUT_SLUG:
                $query['slug'] = $value;
                break;
            case self::FILTERINPUT_DATEFORMAT:
                $query['format'] = $value;
                break;
        }
    }
}
