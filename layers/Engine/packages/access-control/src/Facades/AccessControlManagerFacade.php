<?php

declare(strict_types=1);

namespace PoP\AccessControl\Facades;

use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class AccessControlManagerFacade
{
    public static function getInstance(): AccessControlManagerInterface
    {
        /**
         * @var AccessControlManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(AccessControlManagerInterface::class);
        return $service;
    }
}
