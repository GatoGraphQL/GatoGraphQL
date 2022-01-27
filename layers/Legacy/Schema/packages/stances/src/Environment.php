<?php

declare(strict_types=1);

namespace PoPSchema\Stances;

class Environment
{
    public const STANCE_LIST_DEFAULT_LIMIT = 'STANCE_LIST_DEFAULT_LIMIT';
    public const STANCE_LIST_MAX_LIMIT = 'STANCE_LIST_MAX_LIMIT';

    public static function addStanceTypeToCustomPostUnionTypes(): bool
    {
        return getenv('ADD_STANCE_TYPE_TO_CUSTOMPOST_UNION_TYPES') !== false ? strtolower(getenv('ADD_STANCE_TYPE_TO_CUSTOMPOST_UNION_TYPES')) === "true" : false;
    }
}
