<?php

declare(strict_types=1);

namespace PoP\Routing\Facades;

use PoP\Routing\RoutingManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class RoutingManagerFacade
{
    public static function getInstance(): RoutingManagerInterface
    {
        /**
         * @var RoutingManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(RoutingManagerInterface::class);
        return $service;
    }
}
