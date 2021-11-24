<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CommentsWP\TypeAPIs\CommentTypeAPI;
use PoPWPSchema\Comments\Constants\CommentOrderBy;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            CommentTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            [$this, 'getOrderByQueryArgValue']
        );
    }

    public function getOrderByQueryArgValue(string $orderBy): string
    {
        return match ($orderBy) {
            CommentOrderBy::KARMA => 'comment_karma',
            CommentOrderBy::NONE => 'none',
            default => $orderBy,
        };
    }
}
