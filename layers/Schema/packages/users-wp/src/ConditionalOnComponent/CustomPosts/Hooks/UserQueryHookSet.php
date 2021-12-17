<?php

declare(strict_types=1);

namespace PoPSchema\UsersWP\ConditionalOnComponent\CustomPosts\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\Constants\UserOrderBy;
use PoPSchema\UsersWP\TypeAPIs\UserTypeAPI;

class UserQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            UserTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            [$this, 'getOrderByQueryArgValue']
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
