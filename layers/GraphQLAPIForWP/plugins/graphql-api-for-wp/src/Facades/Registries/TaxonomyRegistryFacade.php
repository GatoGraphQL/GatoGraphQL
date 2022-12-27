<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Registries\TaxonomyRegistryInterface;

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
