<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\EndpointConfiguration;

use GraphQLAPI\GraphQLAPI\EndpointConfiguration\AdminEndpointModuleConfigurationStoreInterface;
use PoP\Root\App;

class AdminEndpointModuleConfigurationStoreFacade
{
    public static function getInstance(): AdminEndpointModuleConfigurationStoreInterface
    {
        /**
         * @var AdminEndpointModuleConfigurationStoreInterface
         */
        $service = App::getContainer()->get(AdminEndpointModuleConfigurationStoreInterface::class);
        return $service;
    }
}
