<?php

declare(strict_types=1);

namespace PoPSchema\Events;

class Environment
{
    public const EVENT_LIST_DEFAULT_LIMIT = 'EVENT_LIST_DEFAULT_LIMIT';
    public const EVENT_LIST_MAX_LIMIT = 'EVENT_LIST_MAX_LIMIT';

    public static function addEventTypeToCustomPostUnionTypes(): bool
    {
        return getenv('ADD_EVENT_TYPE_TO_CUSTOMPOST_UNION_TYPES') !== false ? strtolower(getenv('ADD_EVENT_TYPE_TO_CUSTOMPOST_UNION_TYPES')) == "true" : true;
    }
}
