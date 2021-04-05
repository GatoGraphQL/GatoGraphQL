<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\Routing\RouteNatures;
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
        if ($query->is_single()) {
            return RouteNatures::CUSTOMPOST;
        }

        return $nature;
    }
}
