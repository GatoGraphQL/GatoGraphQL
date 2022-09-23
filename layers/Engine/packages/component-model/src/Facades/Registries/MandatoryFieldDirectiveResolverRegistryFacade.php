<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\Root\App;
use PoP\ComponentModel\Registries\MandatoryFieldDirectiveResolverRegistryInterface;

class MandatoryFieldDirectiveResolverRegistryFacade
{
    public static function getInstance(): MandatoryFieldDirectiveResolverRegistryInterface
    {
        /**
         * @var MandatoryFieldDirectiveResolverRegistryInterface
         */
        $service = App::getContainer()->get(MandatoryFieldDirectiveResolverRegistryInterface::class);
        return $service;
    }
}
