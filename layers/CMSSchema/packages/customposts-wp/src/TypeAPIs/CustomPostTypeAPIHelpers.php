<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\TypeAPIs;

use WP_Post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeAPIHelpers
{
    /**
     * @return array{0: WP_Post, 1: string|int}
     */
    public static function getCustomPostObjectAndID(string|int|object $customPostObjectOrID): array
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
