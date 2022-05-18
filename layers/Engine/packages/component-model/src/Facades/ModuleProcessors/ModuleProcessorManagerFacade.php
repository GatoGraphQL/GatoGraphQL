<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ComponentProcessors;

use PoP\Root\App;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;

class ComponentProcessorManagerFacade
{
    public static function getInstance(): ComponentProcessorManagerInterface
    {
        /**
         * @var ComponentProcessorManagerInterface
         */
        $service = App::getContainer()->get(ComponentProcessorManagerInterface::class);
        return $service;
    }
}
