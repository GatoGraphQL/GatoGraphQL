<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\CustomPostTypeRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomPostTypeRegistryFacade
{
    public static function getInstance(): CustomPostTypeRegistryInterface
    {
        /**
         * @var CustomPostTypeRegistryInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CustomPostTypeRegistryInterface::class);
        return $service;
    }
}
