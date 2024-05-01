<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Container;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Container\ContainerManagerInterface;

class ContainerManagerFacade
{
    public static function getInstance(): ContainerManagerInterface
    {
        /**
         * @var ContainerManagerInterface
         */
        $service = App::getContainer()->get(ContainerManagerInterface::class);
        return $service;
    }
}
