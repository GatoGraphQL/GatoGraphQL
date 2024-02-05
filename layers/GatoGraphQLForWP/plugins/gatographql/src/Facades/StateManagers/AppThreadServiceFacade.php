<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\StateManagers;

use GatoGraphQL\GatoGraphQL\StateManagers\AppThreadServiceInterface;
use PoP\Root\App;

class AppThreadServiceFacade
{
    public static function getInstance(): AppThreadServiceInterface
    {
        /**
         * @var AppThreadServiceInterface
         */
        $service = App::getContainer()->get(AppThreadServiceInterface::class);
        return $service;
    }
}
