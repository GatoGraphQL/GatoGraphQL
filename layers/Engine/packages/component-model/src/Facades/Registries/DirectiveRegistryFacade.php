<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class DirectiveRegistryFacade
{
    public static function getInstance(): DirectiveRegistryInterface
    {
        /**
         * @var DirectiveRegistryInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(DirectiveRegistryInterface::class);
        return $service;
    }
}
