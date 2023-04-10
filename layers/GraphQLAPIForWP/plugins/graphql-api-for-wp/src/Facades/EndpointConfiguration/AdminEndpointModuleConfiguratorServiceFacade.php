<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\EndpointConfiguration;

use GraphQLAPI\GraphQLAPI\EndpointConfiguration\AdminEndpointModuleConfiguratorServiceInterface;
use PoP\Root\App;

class AdminEndpointModuleConfiguratorServiceFacade
{
    public static function getInstance(): AdminEndpointModuleConfiguratorServiceInterface
    {
        /**
         * @var AdminEndpointModuleConfiguratorServiceInterface
         */
        $service = App::getContainer()->get(AdminEndpointModuleConfiguratorServiceInterface::class);
        return $service;
    }
}
