<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ConfigurationComponentModel\HelperServices\TypeResolverHelperServiceInterface;

class TypeResolverHelperServiceFacade
{
    public static function getInstance(): TypeResolverHelperServiceInterface
    {
        /**
         * @var TypeResolverHelperServiceInterface
         */
        $service = App::getContainer()->get(TypeResolverHelperServiceInterface::class);
        return $service;
    }
}
