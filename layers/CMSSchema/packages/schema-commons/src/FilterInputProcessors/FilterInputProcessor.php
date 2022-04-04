<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_SORT = 'filterinput-sort';
    public final const FILTERINPUT_ORDER = 'filterinput-order';
    public final const FILTERINPUT_ORDERBY = 'filterinput-orderby';
    public final const FILTERINPUT_LIMIT = 'filterinput-limit';
    public final const FILTERINPUT_OFFSET = 'filterinput-offset';
    public final const FILTERINPUT_SEARCH = 'filterinput-search';
    public final const FILTERINPUT_DATES = 'filterinput-dates';
    public final const FILTERINPUT_INCLUDE = 'filterinput-include';
    public final const FILTERINPUT_EXCLUDE_IDS = 'filterinput-exclude-ids';
    public final const FILTERINPUT_PARENT_IDS = 'filterinput-parent-ids';
    public final const FILTERINPUT_PARENT_ID = 'filterinput-parent-id';
    public final const FILTERINPUT_EXCLUDE_PARENT_IDS = 'filterinput-exclude-parent-ids';
    public final const FILTERINPUT_SLUGS = 'filterinput-slugs';
    public final const FILTERINPUT_SLUG = 'filterinput-slug';
    public final const FILTERINPUT_PATH_OR_PATHS = 'filterinput-path-or-paths';
    public final const FILTERINPUT_DATEFORMAT = 'filterinput-dateformat';
    public final const FILTERINPUT_GMT = 'filterinput-gmt';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_SORT],
            [self::class, self::FILTERINPUT_ORDER],
            [self::class, self::FILTERINPUT_ORDERBY],
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
            [self::class, self::FILTERINPUT_PATH_OR_PATHS],
            [self::class, self::FILTERINPUT_DATEFORMAT],
            [self::class, self::FILTERINPUT_GMT],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_SORT:
                if (isset($value['orderby'])) {
                    $query['orderby'] = $value['orderby'];
                }
                if (isset($value['order'])) {
                    $query['order'] = $value['order'];
                }
                break;
            case self::FILTERINPUT_ORDER:
                $query['order'] = $value;
                break;
            case self::FILTERINPUT_ORDERBY:
                $query['orderby'] = $value;
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
            case self::FILTERINPUT_PATH_OR_PATHS:
                $query['paths'] = is_array($value) ? $value : [$value];
                break;
            case self::FILTERINPUT_DATEFORMAT:
                $query['format'] = $value;
                break;
            case self::FILTERINPUT_GMT:
                $query['gmt'] = $value;
                break;
        }
    }
}
