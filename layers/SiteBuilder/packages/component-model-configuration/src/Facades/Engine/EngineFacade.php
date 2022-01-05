<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Facades\Engine;

use PoP\Engine\App;
use PoP\ConfigurationComponentModel\Engine\EngineInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EngineFacade
{
    public static function getInstance(): EngineInterface
    {
        /**
         * @var EngineInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(EngineInterface::class);
        return $service;
    }
}
