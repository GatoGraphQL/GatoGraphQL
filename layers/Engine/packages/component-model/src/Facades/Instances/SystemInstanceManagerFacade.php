<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Instances;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;

class SystemInstanceManagerFacade
{
    public static function getInstance(): InstanceManagerInterface
    {
        /**
         * @var InstanceManagerInterface
         */
        $service = SystemContainerBuilderFactory::getInstance()->get(InstanceManagerInterface::class);
        return $service;
    }
}
