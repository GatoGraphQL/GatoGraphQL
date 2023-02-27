<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\Root\App;
use PoP\ComponentModel\Registries\FieldDirectiveResolverRegistryInterface;

class FieldDirectiveResolverRegistryFacade
{
    public static function getInstance(): FieldDirectiveResolverRegistryInterface
    {
        /**
         * @var FieldDirectiveResolverRegistryInterface
         */
        $service = App::getContainer()->get(FieldDirectiveResolverRegistryInterface::class);
        return $service;
    }
}
