<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FilterInput;

class FilterInputHelper
{
    public const NON_EXISTING_CUSTOM_POST_TYPE = 'non-existing-customp-post-type';

    /**
     * If there are no valid postTypes, then force the query to
     * return no results.
     * Otherwise, the query would return the results for post type "post"
     * (the default when postTypes is empty)
     *
     * @param string[] $value
     * @return string[]
     */
    public static function maybeGetNonExistingCustomPostTypes(array $value): array
    {
        if (!$value) {
            // Array of non-existing IDs
            return [
                self::NON_EXISTING_CUSTOM_POST_TYPE,
            ];
        }
        return $value;
    }
}
