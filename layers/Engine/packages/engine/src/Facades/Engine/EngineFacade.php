<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Engine;

use PoP\ComponentModel\Engine\EngineInterface as UpstreamEngineInterface;
use PoP\Engine\Engine\EngineInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EngineFacade
{
    public static function getInstance(): EngineInterface
    {
        /**
         * @var EngineInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(UpstreamEngineInterface::class);
        return $service;
    }
}
