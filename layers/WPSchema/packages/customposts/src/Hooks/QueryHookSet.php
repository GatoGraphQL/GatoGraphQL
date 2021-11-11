<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;
use PoPWPSchema\CustomPosts\Constants\CustomPostOrderBy;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            CustomPostTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            [$this, 'getOrderByQueryArgValue']
        );
    }

    public function getOrderByQueryArgValue(string $orderBy): string
    {
        return match($orderBy) {
            CustomPostOrderBy::COMMENT_COUNT => 'comment_count',
            CustomPostOrderBy::RANDOM => 'rand',
            CustomPostOrderBy::MODIFIED_DATE => 'modified',
            CustomPostOrderBy::RELEVANCE => 'relevance',
            default => $orderBy,
        };
    }
}
