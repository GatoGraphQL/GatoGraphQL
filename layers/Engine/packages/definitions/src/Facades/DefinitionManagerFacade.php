<?php

declare(strict_types=1);

namespace PoP\Definitions\Facades;

use PoP\Root\App;
use PoP\Definitions\DefinitionManagerInterface;

class DefinitionManagerFacade
{
    public static function getInstance(): DefinitionManagerInterface
    {
        /**
         * @var DefinitionManagerInterface
         */
        $service = App::getContainer()->get(DefinitionManagerInterface::class);
        return $service;
    }
}
