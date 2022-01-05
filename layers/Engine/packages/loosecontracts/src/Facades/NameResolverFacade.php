<?php

declare(strict_types=1);

namespace PoP\LooseContracts\Facades;

use PoP\Root\App;
use PoP\LooseContracts\NameResolverInterface;

class NameResolverFacade
{
    public static function getInstance(): NameResolverInterface
    {
        /**
         * @var NameResolverInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(NameResolverInterface::class);
        return $service;
    }
}
