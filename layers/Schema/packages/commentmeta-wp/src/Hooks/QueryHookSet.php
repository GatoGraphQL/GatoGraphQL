<?php

declare(strict_types=1);

namespace PoPSchema\CommentMetaWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CommentsWP\TypeAPIs\CommentTypeAPI;
use PoPSchema\MetaQueryWP\Helpers\MetaQueryHelpers;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            CommentTypeAPI::HOOK_QUERY,
            [MetaQueryHelpers::class, 'convertMetaQuery']
        );
    }
}
