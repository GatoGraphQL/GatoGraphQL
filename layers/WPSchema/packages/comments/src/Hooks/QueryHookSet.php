<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Hooks;

use PoP\Root\Hooks\AbstractHookSet;
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
            CommentOrderBy::AUTHOR_EMAIL => 'comment_author_email',
            CommentOrderBy::AUTHOR_IP => 'comment_author_IP',
            CommentOrderBy::AUTHOR_URL => 'comment_author_url',
            CommentOrderBy::KARMA => 'comment_karma',
            CommentOrderBy::NONE => 'none',
            default => $orderBy,
        };
    }
}
