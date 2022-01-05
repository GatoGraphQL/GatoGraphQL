<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\MutationResolution;

use PoP\Root\App;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;

class MutationResolutionManagerFacade
{
    public static function getInstance(): MutationResolutionManagerInterface
    {
        /**
         * @var MutationResolutionManagerInterface
         */
        $service = App::getContainer()->get(MutationResolutionManagerInterface::class);
        return $service;
    }
}
