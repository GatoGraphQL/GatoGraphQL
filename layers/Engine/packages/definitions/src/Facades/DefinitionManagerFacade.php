<?php

declare(strict_types=1);

namespace PoP\Definitions\Facades;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class DefinitionManagerFacade
{
    public static function getInstance(): DefinitionManagerInterface
    {
        /**
         * @var DefinitionManagerInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(DefinitionManagerInterface::class);
        return $service;
    }
}
