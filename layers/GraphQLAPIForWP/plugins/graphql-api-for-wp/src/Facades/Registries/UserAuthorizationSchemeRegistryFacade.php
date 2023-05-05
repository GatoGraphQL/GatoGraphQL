<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\UserAuthorizationSchemeRegistryInterface;

class UserAuthorizationSchemeRegistryFacade
{
    public static function getInstance(): UserAuthorizationSchemeRegistryInterface
    {
        /**
         * @var UserAuthorizationSchemeRegistryInterface
         */
        $service = App::getContainer()->get(UserAuthorizationSchemeRegistryInterface::class);
        return $service;
    }
}
