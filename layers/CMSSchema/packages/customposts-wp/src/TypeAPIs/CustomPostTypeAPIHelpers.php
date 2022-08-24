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
     * @return array{0:WP_Post|null,1:null|string|int}
     */
    public static function getCustomPostObjectAndID(string|int|object $customPostObjectOrID): array
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = $customPostObjectOrID;
            /** @var WP_Post|null */
            $customPost = \get_post((int)$customPostID);
        }
        return [
            $customPost,
            $customPostID,
        ];
    }
}
