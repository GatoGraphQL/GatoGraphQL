<?php

declare(strict_types=1);

namespace PoP\AccessControl\Facades;

use PoP\Root\App;
use PoP\AccessControl\Services\AccessControlManagerInterface;

class AccessControlManagerFacade
{
    public static function getInstance(): AccessControlManagerInterface
    {
        /**
         * @var AccessControlManagerInterface
         */
        $service = App::getContainer()->get(AccessControlManagerInterface::class);
        return $service;
    }
}
