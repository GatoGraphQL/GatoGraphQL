<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Engine;

use PoP\Root\App;
use PoP\ComponentModel\Engine\MandatoryFieldDirectiveResolverRegistryInterface;

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
