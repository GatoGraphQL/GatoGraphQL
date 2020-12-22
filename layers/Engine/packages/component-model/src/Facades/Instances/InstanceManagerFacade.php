<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Instances;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class InstanceManagerFacade
{
    public static function getInstance(): InstanceManagerInterface
    {
        /**
         * @var InstanceManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(InstanceManagerInterface::class);
        return $service;
    }
}
