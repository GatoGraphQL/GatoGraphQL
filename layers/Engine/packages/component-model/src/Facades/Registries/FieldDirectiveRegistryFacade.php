<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\Root\App;
use PoP\ComponentModel\Registries\FieldDirectiveRegistryInterface;

class FieldDirectiveRegistryFacade
{
    public static function getInstance(): FieldDirectiveRegistryInterface
    {
        /**
         * @var FieldDirectiveRegistryInterface
         */
        $service = App::getContainer()->get(FieldDirectiveRegistryInterface::class);
        return $service;
    }
}
