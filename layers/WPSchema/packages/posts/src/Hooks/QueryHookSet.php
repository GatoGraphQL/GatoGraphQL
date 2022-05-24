<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\PostsWP\TypeAPIs\PostTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    public final const NON_EXISTING_CUSTOM_POST_ID = 'non-existing-id';

    protected function init(): void
    {
        App::addFilter(
            PostTypeAPI::HOOK_QUERY,
            $this->convertCustomPostsQuery(...),
            10,
            2
        );
    }

    public function convertCustomPostsQuery($query, array $options): array
    {
        if (isset($query['is-sticky'])) {
            $stickyPosts = \get_option('sticky_posts', []);

            // Add the sticky posts to whichever posts were already set
            if ($query['is-sticky']) {
                // If there are no sticky posts, then the result must be empty
                if ($stickyPosts === []) {
                    $stickyPosts = [self::NON_EXISTING_CUSTOM_POST_ID];
                }
                $query['post__in'] = array_merge(
                    $query['post__in'] ?? [],
                    $stickyPosts
                );
            } else {
                $query['post__not_in'] = array_merge(
                    $query['post__not_in'] ?? [],
                    $stickyPosts
                );
            }
            unset($query['is-sticky']);
        }
        return $query;
    }
}
