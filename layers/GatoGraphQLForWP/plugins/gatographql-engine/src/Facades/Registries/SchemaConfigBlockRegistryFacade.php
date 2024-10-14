<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\SchemaConfigBlockRegistryInterface;

class SchemaConfigBlockRegistryFacade
{
    public static function getInstance(): SchemaConfigBlockRegistryInterface
    {
        /**
         * @var SchemaConfigBlockRegistryInterface
         */
        $service = App::getContainer()->get(SchemaConfigBlockRegistryInterface::class);
        return $service;
    }
}
