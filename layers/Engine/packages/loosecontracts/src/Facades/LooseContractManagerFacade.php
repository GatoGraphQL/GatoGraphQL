<?php

declare(strict_types=1);

namespace PoP\LooseContracts\Facades;

use PoP\Root\App;
use PoP\LooseContracts\LooseContractManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class LooseContractManagerFacade
{
    public static function getInstance(): LooseContractManagerInterface
    {
        /**
         * @var LooseContractManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(LooseContractManagerInterface::class);
        return $service;
    }
}
