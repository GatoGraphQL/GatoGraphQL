<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Checkpoints;

use PoP\Root\App;
use PoP\ComponentModel\Checkpoints\CheckpointManagerInterface;

class CheckpointManagerFacade
{
    public static function getInstance(): CheckpointManagerInterface
    {
        /**
         * @var CheckpointManagerInterface
         */
        $service = App::getContainer()->get(CheckpointManagerInterface::class);
        return $service;
    }
}
