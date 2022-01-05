<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Facades\Engine;

use PoP\ConfigurationComponentModel\Engine\EngineInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EngineFacade
{
    public static function getInstance(): EngineInterface
    {
        /**
         * @var EngineInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(EngineInterface::class);
        return $service;
    }
}
