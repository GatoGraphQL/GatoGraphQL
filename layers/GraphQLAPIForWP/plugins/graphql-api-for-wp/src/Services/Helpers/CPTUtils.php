<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use WP_Post;

class CPTUtils
{
    /**
     * Get the description of the post, defined in the excerpt
     */
    public function getCustomPostDescription(WP_Post $post): string
    {
        return strip_tags($post->post_excerpt ?? '');
    }
}
