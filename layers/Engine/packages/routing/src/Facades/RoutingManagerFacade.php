<?php

declare(strict_types=1);

namespace PoP\Routing\Facades;

use PoP\Root\App;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Routing\RoutingManagerInterface;

class RoutingManagerFacade
{
    public static function getInstance(): RoutingManagerInterface
    {
        /**
         * @var RoutingManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(RoutingManagerInterface::class);
        return $service;
    }
}
