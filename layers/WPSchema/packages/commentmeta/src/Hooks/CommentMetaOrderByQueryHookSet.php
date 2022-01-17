<?php

declare(strict_types=1);

namespace PoPWPSchema\CommentMeta\Hooks;

use PoPCMSSchema\CommentsWP\TypeAPIs\CommentTypeAPI;
use PoPWPSchema\Meta\Constants\MetaOrderBy;
use PoPWPSchema\Meta\Hooks\AbstractMetaOrderByQueryHookSet;

class CommentMetaOrderByQueryHookSet extends AbstractMetaOrderByQueryHookSet
{
    protected function getHookName(): string
    {
        return CommentTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE;
    }

    public function getOrderByQueryArgValue(string $orderBy): string
    {
        return match ($orderBy) {
            MetaOrderBy::META_VALUE => 'comment_meta_value',
            default => parent::getOrderByQueryArgValue($orderBy),
        };
    }
}
