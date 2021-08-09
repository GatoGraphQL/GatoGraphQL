<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMetaWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;
use PoPSchema\MetaQueryWP\Helpers\MetaQueryHelpers;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            CustomPostTypeAPI::HOOK_QUERY,
            [MetaQueryHelpers::class, 'convertMetaQuery']
        );
    }
}
