<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Endpoints;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Environment;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers\AbstractRESTController;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers\CPTBlockAttributesAdminRESTController;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers\ModulesAdminRESTController;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers\ModuleSettingsAdminRESTController;

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
