<?php

declare(strict_types=1);

namespace PoP\Routing\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Routing\RoutingManagerInterface;

class RoutingManagerFacade
{
    public static function getInstance(): RoutingManagerInterface
    {
        /**
         * @var RoutingManagerInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(RoutingManagerInterface::class);
        return $service;
    }
}
