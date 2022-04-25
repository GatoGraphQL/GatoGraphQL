<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers;

use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use WP_Error;

trait WithModuleParamRESTControllerTrait
{
    /**
     * Validate there is a module with this ID
     */
    protected function validateModule(string $moduleID): bool|WP_Error
    {
        $module = $this->getModuleByID($moduleID);
        if ($module === null) {
            return new WP_Error(
                '1',
                sprintf(
                    __('There is no module with ID \'%s\'', 'graphql-api-testing'),
                    $moduleID
                ),
                [
                    'moduleID' => $moduleID,
                ]
            );
        }
        return true;
    }

    public function getModuleByID(string $moduleID): ?string
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules();
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            if ($moduleID === $moduleResolver->getID($module)) {
                return $module;
            }
        }
        return null;
    }
}
