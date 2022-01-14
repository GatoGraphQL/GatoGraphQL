<?php

declare(strict_types=1);

namespace PoP\RoutingWP;

use PoP\Root\App;
use PoP\Root\Routing\AbstractRoutingManager;
use PoP\Root\Routing\RouteNatures;
use WP_Query;

class WPQueryRoutingManager extends AbstractRoutingManager implements WPQueryRoutingManagerInterface
{
    use RoutingManagerTrait;

    /**
     * @var string[]|null
     */
    private ?array $routes = null;

    /**
     * @return string[]
     */
    public function getRoutes(): array
    {
        if ($this->routes === null) {
            $this->routes = array_filter(
                (array) App::applyFilters(
                    HookNames::ROUTES,
                    []
                )
            );
        }
        return $this->routes;
    }

    public function getCurrentNature(): string
    {
        $this->init();

        /** @var WP_Query */
        $query = $this->query;
        if ($this->isGeneric()) {
            return RouteNatures::GENERIC;
        }
        if ($query->is_home() || $query->is_front_page()) {
            return RouteNatures::HOME;
        }
        if ($query->is_404()) {
            return RouteNatures::NOTFOUND;
        }

        // Allow plugins to implement their own natures
        return (string) App::applyFilters(
            HookNames::NATURE,
            parent::getCurrentNature(),
            $this->query
        );
    }
}
