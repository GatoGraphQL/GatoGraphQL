<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\TaxonomyRegistryInterface;

class TaxonomyRegistryFacade
{
    public static function getInstance(): TaxonomyRegistryInterface
    {
        /**
         * @var TaxonomyRegistryInterface
         */
        $service = App::getContainer()->get(TaxonomyRegistryInterface::class);
        return $service;
    }
}
