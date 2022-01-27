<?php

declare(strict_types=1);

namespace PoP\Root\Facades\Routing;

use PoP\Root\App;
use PoP\Root\Routing\RoutingManagerInterface;

class RoutingManagerFacade
{
    public static function getInstance(): RoutingManagerInterface
    {
        /**
         * @var RoutingManagerInterface
         */
        $service = App::getContainer()->get(RoutingManagerInterface::class);
        return $service;
    }
}
