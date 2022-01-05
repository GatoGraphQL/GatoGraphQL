<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Facades\Engine;

use PoP\Engine\App;
use PoP\ConfigurationComponentModel\Engine\EngineInterface;

class EngineFacade
{
    public static function getInstance(): EngineInterface
    {
        /**
         * @var EngineInterface
         */
        $service = App::getContainer()->get(EngineInterface::class);
        return $service;
    }
}
