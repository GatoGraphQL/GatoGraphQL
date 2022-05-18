<?php

declare(strict_types=1);

namespace PoP\ComponentRouting\Facades;

use PoP\Root\App;
use PoP\ComponentRouting\ComponentRoutingProcessorManagerInterface;

class ComponentRoutingProcessorManagerFacade
{
    public static function getInstance(): ComponentRoutingProcessorManagerInterface
    {
        /**
         * @var ComponentRoutingProcessorManagerInterface
         */
        $service = App::getContainer()->get(ComponentRoutingProcessorManagerInterface::class);
        return $service;
    }
}
