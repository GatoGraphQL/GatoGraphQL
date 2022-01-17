<?php

declare(strict_types=1);

namespace PoPWPSchema\UserMeta\Hooks;

use PoPCMSSchema\UsersWP\TypeAPIs\UserTypeAPI;
use PoPWPSchema\Meta\Hooks\AbstractMetaOrderByQueryHookSet;

class UserMetaOrderByQueryHookSet extends AbstractMetaOrderByQueryHookSet
{
    protected function getHookName(): string
    {
        return UserTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE;
    }
}
