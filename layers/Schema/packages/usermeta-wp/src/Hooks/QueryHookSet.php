<?php

declare(strict_types=1);

namespace PoPSchema\UserMetaWP\Hooks;

use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\MetaQueryWP\Helpers\MetaQueryHelpers;
use PoPSchema\UsersWP\TypeAPIs\UserTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        \PoP\Root\App::getHookManager()->addFilter(
            UserTypeAPI::HOOK_QUERY,
            [MetaQueryHelpers::class, 'convertMetaQuery']
        );
    }
}
