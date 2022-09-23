<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\Registries;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Registries\MandatoryOperationDirectiveResolverRegistryInterface;

class MandatoryOperationDirectiveResolverRegistryFacade
{
    public static function getInstance(): MandatoryOperationDirectiveResolverRegistryInterface
    {
        /**
         * @var MandatoryOperationDirectiveResolverRegistryInterface
         */
        $service = App::getContainer()->get(MandatoryOperationDirectiveResolverRegistryInterface::class);
        return $service;
    }
}
