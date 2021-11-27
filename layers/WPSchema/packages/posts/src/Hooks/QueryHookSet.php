<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\Hooks;

use PoP\Hooks\AbstractHookSet;
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
        if (isset($query['ignore-sticky'])) {
            $query['ignore_sticky_posts'] = $query['ignore-sticky'];
            unset($query['ignore-sticky']);
        }
        if (isset($query['exclude-sticky']) && $query['exclude-sticky']) {
            // Add the sticky posts to whichever post was already set to be excluded
            $query['post__not_in'] = array_merge(
                $query['post__not_in'] ?? [],
                \get_option('sticky_posts', [])
            );
            unset($query['exclude-sticky']);
        }
        return $query;
    }
}
