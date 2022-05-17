<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP\ConditionalOnModule\CustomPosts\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\Constants\UserOrderBy;
use PoPCMSSchema\UsersWP\TypeAPIs\UserTypeAPI;

class UserQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            UserTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            $this->getOrderByQueryArgValue(...)
        );
    }

    public function getOrderByQueryArgValue(string $orderBy): string
    {
        return match ($orderBy) {
            UserOrderBy::CUSTOMPOST_COUNT => 'post_count',
            default => $orderBy,
        };
    }
}
