<?php

declare(strict_types=1);

namespace PoP\Root\Facades;

use PoP\Root\App;
use PoP\Root\Registries\CompilerPassRegistryInterface;

class SystemCompilerPassRegistryFacade
{
    public static function getInstance(): CompilerPassRegistryInterface
    {
        $systemContainerBuilder = App::getSystemContainerBuilderFactory()->getInstance();
        /**
         * @var CompilerPassRegistryInterface
         */
        $service = $systemContainerBuilder->get(CompilerPassRegistryInterface::class);
        return $service;
    }
}
