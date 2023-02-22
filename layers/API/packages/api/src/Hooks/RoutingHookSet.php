<?php

declare(strict_types=1);

namespace PoPAPI\API\Hooks;

use PoP\ComponentModel\Engine\Engine;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class RoutingHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            Engine::HOOK_EXTRA_ROUTES,
            $this->getExtraRoutes(...),
            10,
            1
        );
    }

    /**
     * The API cannot use getExtraRoutes()!!!!!
     *
     * Because the fields can't be applied to different resources!
     * (Eg: author/leo/ and author/leo/?route=posts)
     *
     * @param string[] $extraRoutes
     * @return string[]
     */
    public function getExtraRoutes(array $extraRoutes): array
    {
        if (App::getState('scheme') === APISchemes::API) {
            return [];
        }

        return $extraRoutes;
    }
}
