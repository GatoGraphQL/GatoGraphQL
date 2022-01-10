<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\MutationResolution;

use PoP\Root\App;
use PoP\ComponentModel\MutationResolution\MutationResolutionStoreInterface;

class MutationResolutionStoreFacade
{
    public static function getInstance(): MutationResolutionStoreInterface
    {
        /**
         * @var MutationResolutionStoreInterface
         */
        $service = App::getContainer()->get(MutationResolutionStoreInterface::class);
        return $service;
    }
}
