<?php

declare(strict_types=1);

namespace PoP\CacheControl\Facades;

use PoP\Root\App;
use PoP\CacheControl\Managers\CacheControlEngineInterface;

class CacheControlEngineFacade
{
    public static function getInstance(): CacheControlEngineInterface
    {
        /**
         * @var CacheControlEngineInterface
         */
        $service = App::getContainer()->get(CacheControlEngineInterface::class);
        return $service;
    }
}
