<?php

declare(strict_types=1);

namespace PoPWPSchema\CommentMeta\Hooks;

use PoPSchema\CommentsWP\TypeAPIs\CommentTypeAPI;
use PoPWPSchema\Meta\Hooks\AbstractMetaOrderByQueryHookSet;

class CommentMetaOrderByQueryHookSet extends AbstractMetaOrderByQueryHookSet
{
    protected function getHookName(): string
    {
        return CommentTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE;
    }
}
