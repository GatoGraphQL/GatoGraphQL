<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoPSchema\PostsWP\TypeAPIs\PostTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            PostTypeAPI::HOOK_QUERY,
            [$this, 'convertCustomPostsQuery'],
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
