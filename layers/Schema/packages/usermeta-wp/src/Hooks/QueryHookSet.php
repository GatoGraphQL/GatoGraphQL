<?php

declare(strict_types=1);

namespace PoPSchema\UserMetaWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\MetaQueryWP\Helpers\MetaQueryHelpers;
use PoPSchema\UsersWP\TypeAPIs\UserTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            UserTypeAPI::HOOK_QUERY,
            [MetaQueryHelpers::class, 'convertMetaQuery']
        );
    }
}
