<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\CustomEndpointAnnotatorRegistryInterface;

class CustomEndpointAnnotatorRegistryFacade
{
    public static function getInstance(): CustomEndpointAnnotatorRegistryInterface
    {
        /**
         * @var CustomEndpointAnnotatorRegistryInterface
         */
        $service = App::getContainer()->get(CustomEndpointAnnotatorRegistryInterface::class);
        return $service;
    }
}
