<?php

declare(strict_types=1);

namespace PoP\RoutingWP;

use PoP\Root\App;
use PoP\Routing\AbstractRoutingManager;
use PoP\Routing\RouteNatures;
use WP_Query;

class RoutingManager extends AbstractRoutingManager
{
    use RoutingManagerTrait;

    public function getCurrentNature(): string
    {
        $this->init();
        /** @var WP_Query */
        $query = $this->query;
        if ($this->isStandard()) {
            return RouteNatures::GENERIC;
        } elseif ($query->is_home() || $query->is_front_page()) {
            return RouteNatures::HOME;
        } elseif ($query->is_404()) {
            return RouteNatures::NOTFOUND;
        }

        // Allow plugins to implement their own natures
        return (string) App::applyFilters(
            'WPCMSRoutingState:nature',
            parent::getCurrentNature(),
            $this->query
        );
    }
}
