<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\Root\App;
use PoP\ComponentModel\Registries\OperationDependencyDefinerDirectiveRegistryInterface;

class OperationDependencyDefinerDirectiveRegistryFacade
{
    public static function getInstance(): OperationDependencyDefinerDirectiveRegistryInterface
    {
        /**
         * @var OperationDependencyDefinerDirectiveRegistryInterface
         */
        $service = App::getContainer()->get(OperationDependencyDefinerDirectiveRegistryInterface::class);
        return $service;
    }
}
