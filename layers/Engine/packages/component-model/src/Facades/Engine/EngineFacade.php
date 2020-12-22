<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Engine;

use PoP\ComponentModel\Engine\EngineInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EngineFacade
{
    public static function getInstance(): EngineInterface
    {
        /**
         * @var EngineInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(EngineInterface::class);
        return $service;
    }
}
