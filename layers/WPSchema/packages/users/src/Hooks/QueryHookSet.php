<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\Hooks;

use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\UsersWP\TypeAPIs\UserTypeAPI;
use PoPWPSchema\Users\Constants\UserOrderBy;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        \PoP\Root\App::getHookManager()->addFilter(
            UserTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            [$this, 'getOrderByQueryArgValue']
        );
    }

    public function getOrderByQueryArgValue(string $orderBy): string
    {
        return match ($orderBy) {
            UserOrderBy::INCLUDE => 'include',
            UserOrderBy::WEBSITE_URL => 'user_url',
            UserOrderBy::NICENAME => 'user_nicename',
            UserOrderBy::EMAIL => 'user_email',
            default => $orderBy,
        };
    }
}
