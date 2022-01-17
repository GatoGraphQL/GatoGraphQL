<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\RootWP\Routing\HookNames;
use PoPCMSSchema\Users\Routing\RequestNature;
use WP_Query;

class RoutingStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::NATURE,
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
            return RequestNature::USER;
        }

        return $nature;
    }
}
