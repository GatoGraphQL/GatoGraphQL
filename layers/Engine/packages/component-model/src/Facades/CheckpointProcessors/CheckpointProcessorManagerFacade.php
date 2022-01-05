<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\CheckpointProcessors;

use PoP\Root\App;
use PoP\ComponentModel\CheckpointProcessors\CheckpointProcessorManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CheckpointProcessorManagerFacade
{
    public static function getInstance(): CheckpointProcessorManagerInterface
    {
        /**
         * @var CheckpointProcessorManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(CheckpointProcessorManagerInterface::class);
        return $service;
    }
}
