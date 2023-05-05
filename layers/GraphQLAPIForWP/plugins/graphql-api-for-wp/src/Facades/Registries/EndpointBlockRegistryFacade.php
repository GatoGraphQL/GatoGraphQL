<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\EndpointBlockRegistryInterface;

class EndpointBlockRegistryFacade
{
    public static function getInstance(): EndpointBlockRegistryInterface
    {
        /**
         * @var EndpointBlockRegistryInterface
         */
        $service = App::getContainer()->get(EndpointBlockRegistryInterface::class);
        return $service;
    }
}
