<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

use WP_Post;

class CPTUtils
{
    /**
     * Get the description of the post, defined in the excerpt
     */
    public static function getCustomPostDescription(WP_Post $post): string
    {
        return strip_tags($post->post_excerpt ?? '');
    }
}
