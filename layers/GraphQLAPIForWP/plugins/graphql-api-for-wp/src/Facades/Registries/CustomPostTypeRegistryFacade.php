<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Registries\CustomPostTypeRegistryInterface;

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
