<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\CustomPostTypeRegistryInterface;

class CustomPostTypeRegistryFacade
{
    public static function getInstance(): CustomPostTypeRegistryInterface
    {
        /**
         * @var CustomPostTypeRegistryInterface
         */
        $service = App::getContainer()->get(CustomPostTypeRegistryInterface::class);
        return $service;
    }
}
