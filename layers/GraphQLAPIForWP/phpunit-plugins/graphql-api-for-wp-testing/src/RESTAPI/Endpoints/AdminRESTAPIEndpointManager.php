<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Endpoints;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Environment;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\AbstractRESTController;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\CPTBlockAttributesAdminRESTController;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\ModulesAdminRESTController;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\ModuleSettingsAdminRESTController;

class AdminRESTAPIEndpointManager extends AbstractRESTAPIEndpointManager
{
    /**
     * @return AbstractRESTController[]
     */
    protected function getControllers(): array
    {
        return [
            new ModuleSettingsAdminRESTController(),
            new ModulesAdminRESTController(),
            new CPTBlockAttributesAdminRESTController(
                Environment::getSupportedPluginNamespaces(),
            ),
        ];
    }
}
