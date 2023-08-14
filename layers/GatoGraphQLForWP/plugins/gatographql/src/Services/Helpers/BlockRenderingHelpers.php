<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use WP_Post;

class BlockRenderingHelpers
{
    /**
     * Get a standardized title for a Custom Post
     */
    public function getCustomPostTitle(WP_Post $customPostObject): string
    {
        $title = $customPostObject->post_title ?
            $customPostObject->post_title :
            \__('(No title)', 'gatographql');

        // If the post is either draft/pending (or maybe trash?), add that info in the title
        if ($customPostObject->post_status !== 'publish') {
            $title = sprintf(
                \__('(%s) %s', 'gatographql'),
                ucwords($customPostObject->post_status),
                $title
            );
        }
        return $title;
    }
}
