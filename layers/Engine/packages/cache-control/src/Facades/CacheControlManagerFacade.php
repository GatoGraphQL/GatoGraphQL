<?php

declare(strict_types=1);

namespace PoP\CacheControl\Facades;

use PoP\Root\App;
use PoP\CacheControl\Managers\CacheControlManagerInterface;

class CacheControlManagerFacade
{
    public static function getInstance(): CacheControlManagerInterface
    {
        /**
         * @var CacheControlManagerInterface
         */
        $service = App::getContainer()->get(CacheControlManagerInterface::class);
        return $service;
    }
}
