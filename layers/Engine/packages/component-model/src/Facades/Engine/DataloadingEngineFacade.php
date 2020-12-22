<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Engine;

use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class DataloadingEngineFacade
{
    public static function getInstance(): DataloadingEngineInterface
    {
        /**
         * @var DataloadingEngineInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(DataloadingEngineInterface::class);
        return $service;
    }
}
