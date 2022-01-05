<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\MutationResolution;

use PoP\Root\App;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class MutationResolutionManagerFacade
{
    public static function getInstance(): MutationResolutionManagerInterface
    {
        /**
         * @var MutationResolutionManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(MutationResolutionManagerInterface::class);
        return $service;
    }
}
