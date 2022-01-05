<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Registries;

use PoP\Root\App;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;

class DirectiveRegistryFacade
{
    public static function getInstance(): DirectiveRegistryInterface
    {
        /**
         * @var DirectiveRegistryInterface
         */
        $service = App::getContainer()->get(DirectiveRegistryInterface::class);
        return $service;
    }
}
