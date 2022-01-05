<?php

declare(strict_types=1);

namespace PoP\LooseContracts\Facades;

use PoP\Root\App;
use PoP\LooseContracts\LooseContractManagerInterface;

class LooseContractManagerFacade
{
    public static function getInstance(): LooseContractManagerInterface
    {
        /**
         * @var LooseContractManagerInterface
         */
        $service = App::getContainer()->get(LooseContractManagerInterface::class);
        return $service;
    }
}
