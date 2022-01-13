<?php

declare(strict_types=1);

namespace PoPSchema\UsersWP\Hooks;

use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\Users\Routing\RouteNatures;
use WP_Query;

class RoutingStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            'WPCMSRoutingState:nature',
            [$this, 'getNature'],
            10,
            2
        );
    }

    /**
     * The nature of the route
     */
    public function getNature(string $nature, WP_Query $query): string
    {
        if ($query->is_author()) {
            return RouteNatures::USER;
        }

        return $nature;
    }
}
