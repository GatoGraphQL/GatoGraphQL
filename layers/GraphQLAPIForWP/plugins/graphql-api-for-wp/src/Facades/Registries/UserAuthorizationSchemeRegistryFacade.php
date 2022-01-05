<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\UserAuthorizationSchemeRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class UserAuthorizationSchemeRegistryFacade
{
    public static function getInstance(): UserAuthorizationSchemeRegistryInterface
    {
        /**
         * @var UserAuthorizationSchemeRegistryInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(UserAuthorizationSchemeRegistryInterface::class);
        return $service;
    }
}
