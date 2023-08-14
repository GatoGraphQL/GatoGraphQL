<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers;

use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use WP_Error;

trait WithModuleParamRESTControllerTrait
{
    /**
     * @return array<string,mixed>
     */
    protected function getModuleIDParamArgs(): array
    {
        return [
            'description' => __('Module ID', 'gatographql-testing'),
            'type' => 'string',
            'required' => true,
            'validate_callback' => $this->validateModule(...),
        ];
    }

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
                    __('There is no module with ID \'%s\'', 'gatographql'),
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
