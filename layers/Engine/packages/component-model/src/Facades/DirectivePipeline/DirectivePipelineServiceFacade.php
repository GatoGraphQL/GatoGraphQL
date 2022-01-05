<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\DirectivePipeline;

use PoP\Root\App;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;

class DirectivePipelineServiceFacade
{
    public static function getInstance(): DirectivePipelineServiceInterface
    {
        /**
         * @var DirectivePipelineServiceInterface
         */
        $service = App::getContainer()->get(DirectivePipelineServiceInterface::class);
        return $service;
    }
}
