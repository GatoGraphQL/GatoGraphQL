<?php

declare(strict_types=1);

namespace PoPSchema\TagsWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\Tags\Routing\RouteNatures;
use WP_Query;

class RoutingStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
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
        if ($query->is_tag()) {
            return RouteNatures::TAG;
        }

        return $nature;
    }
}
