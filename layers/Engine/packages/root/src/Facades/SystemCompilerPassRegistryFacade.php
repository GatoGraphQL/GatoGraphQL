<?php

declare(strict_types=1);

namespace PoP\Root\Facades;

use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Registries\CompilerPassRegistryInterface;

class SystemCompilerPassRegistryFacade
{
    public static function getInstance(): CompilerPassRegistryInterface
    {
        $systemContainerBuilder = \PoP\Root\App::getSystemContainerBuilderFactory()->getInstance();
        /**
         * @var CompilerPassRegistryInterface
         */
        $service = $systemContainerBuilder->get(CompilerPassRegistryInterface::class);
        return $service;
    }
}
