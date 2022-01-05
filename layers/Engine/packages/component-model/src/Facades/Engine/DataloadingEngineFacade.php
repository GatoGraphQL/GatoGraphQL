<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Engine;

use PoP\Root\App;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;

class DataloadingEngineFacade
{
    public static function getInstance(): DataloadingEngineInterface
    {
        /**
         * @var DataloadingEngineInterface
         */
        $service = App::getContainer()->get(DataloadingEngineInterface::class);
        return $service;
    }
}
