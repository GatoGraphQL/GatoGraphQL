<?php

declare(strict_types=1);

namespace PoP\Root\Facades\Instances;

use PoP\Root\Instances\InstanceManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class InstanceManagerFacade
{
    public static function getInstance(): InstanceManagerInterface
    {
        /**
         * @var InstanceManagerInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(InstanceManagerInterface::class);
        return $service;
    }
}
