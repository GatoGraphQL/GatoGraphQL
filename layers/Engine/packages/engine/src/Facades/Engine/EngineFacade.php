<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Engine;

use PoP\Root\App;
use PoP\ComponentModel\Engine\EngineInterface as UpstreamEngineInterface;
use PoP\Engine\Engine\EngineInterface;

class EngineFacade
{
    public static function getInstance(): EngineInterface
    {
        /**
         * @var EngineInterface
         */
        $service = App::getContainer()->get(UpstreamEngineInterface::class);
        return $service;
    }
}
