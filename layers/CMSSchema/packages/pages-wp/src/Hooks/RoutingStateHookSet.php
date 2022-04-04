<?php

declare(strict_types=1);

namespace PoPCMSSchema\PagesWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\RootWP\Routing\HookNames;
use PoPCMSSchema\Pages\Routing\RequestNature;
use WP_Query;

class RoutingStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::NATURE,
            $this->getNature(...),
            10,
            2
        );
    }

    /**
     * The nature of the route
     */
    public function getNature(string $nature, WP_Query $query): string
    {
        if ($query->is_page()) {
            return RequestNature::PAGE;
        }

        return $nature;
    }
}
