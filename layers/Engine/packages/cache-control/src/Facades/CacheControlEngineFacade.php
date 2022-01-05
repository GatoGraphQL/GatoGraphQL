<?php

declare(strict_types=1);

namespace PoP\CacheControl\Facades;

use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CacheControlEngineFacade
{
    public static function getInstance(): CacheControlEngineInterface
    {
        /**
         * @var CacheControlEngineInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(CacheControlEngineInterface::class);
        return $service;
    }
}
