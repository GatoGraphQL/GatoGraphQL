<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\MutationResolution;

use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class MutationResolutionManagerFacade
{
    public static function getInstance(): MutationResolutionManagerInterface
    {
        /**
         * @var MutationResolutionManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(MutationResolutionManagerInterface::class);
        return $service;
    }
}
