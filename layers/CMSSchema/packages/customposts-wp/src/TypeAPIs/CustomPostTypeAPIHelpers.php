<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeAPIHelpers
{
    public static function getCustomPostObjectAndID(string | int | object $customPostObjectOrID): array
    {
        if (is_object($customPostObjectOrID)) {
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = $customPostObjectOrID;
            $customPost = \get_post($customPostID);
        }
        return [
            $customPost,
            $customPostID,
        ];
    }
}
