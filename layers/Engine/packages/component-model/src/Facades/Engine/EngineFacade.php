<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Engine;

use PoP\Root\App;
use PoP\ComponentModel\Engine\EngineInterface;

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
