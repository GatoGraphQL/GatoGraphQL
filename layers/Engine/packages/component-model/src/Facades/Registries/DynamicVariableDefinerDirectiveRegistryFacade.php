<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\Root\App;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;

class DynamicVariableDefinerDirectiveRegistryFacade
{
    public static function getInstance(): DynamicVariableDefinerDirectiveRegistryInterface
    {
        /**
         * @var DynamicVariableDefinerDirectiveRegistryInterface
         */
        $service = App::getContainer()->get(DynamicVariableDefinerDirectiveRegistryInterface::class);
        return $service;
    }
}
