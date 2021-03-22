<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts;

class Environment
{
    public const LOCATIONPOST_LIST_DEFAULT_LIMIT = 'LOCATIONPOST_LIST_DEFAULT_LIMIT';
    public const LOCATIONPOST_LIST_MAX_LIMIT = 'LOCATIONPOST_LIST_MAX_LIMIT';

    /**
     * Customize the Location Post type name
     */
    public static function getLocationPostTypeName(): ?string
    {
        return getenv('LOCATION_POST_TYPE_NAME') !== false ? getenv('LOCATION_POST_TYPE_NAME') : null;
    }
    public static function addLocationPostTypeToCustomPostUnionTypes(): bool
    {
        return getenv('ADD_LOCATIONPOST_TYPE_TO_CUSTOMPOST_UNION_TYPES') !== false ? strtolower(getenv('ADD_LOCATIONPOST_TYPE_TO_CUSTOMPOST_UNION_TYPES')) == "true" : false;
    }
}
