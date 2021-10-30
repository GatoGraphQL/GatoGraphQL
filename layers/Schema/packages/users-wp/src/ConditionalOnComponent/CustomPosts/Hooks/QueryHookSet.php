<?php

declare(strict_types=1);

namespace PoPSchema\UsersWP\ConditionalOnComponent\CustomPosts\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            AbstractCustomPostTypeAPI::HOOK_QUERY,
            [$this, 'convertCustomPostsQuery'],
            10,
            2
        );
    }

    public function convertCustomPostsQuery(array $query, array $options): array
    {
        if (isset($query['author-ids'])) {
            $query['author__in'] = $query['author-ids'];
            unset($query['author-ids']);
        }
        if (isset($query['author-slug'])) {
            $query['author_name'] = $query['author-slug'];
            unset($query['author-slug']);
        }
        if (isset($query['exclude-author-ids'])) {
            $query['author__not_in'] = $query['exclude-author-ids'];
            unset($query['exclude-author-ids']);
        }
        return $query;
    }
}
