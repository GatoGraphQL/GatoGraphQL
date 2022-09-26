<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\Root\App;
use PoP\ComponentModel\Registries\OperationDependencyDirectiveRegistryInterface;

class OperationDependencyDirectiveRegistryFacade
{
    public static function getInstance(): OperationDependencyDirectiveRegistryInterface
    {
        /**
         * @var OperationDependencyDirectiveRegistryInterface
         */
        $service = App::getContainer()->get(OperationDependencyDirectiveRegistryInterface::class);
        return $service;
    }
}
