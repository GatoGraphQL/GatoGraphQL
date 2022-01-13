<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMetaWP\Hooks;

use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPSchema\MetaQueryWP\Helpers\MetaQueryHelpers;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            AbstractCustomPostTypeAPI::HOOK_QUERY,
            [MetaQueryHelpers::class, 'convertMetaQuery']
        );
    }
}
