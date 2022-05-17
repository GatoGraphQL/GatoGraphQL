<?php

declare(strict_types=1);

namespace PoPAPI\API\Engine;

use PoP\Root\App;
use PoPAPI\API\Module as APIModule;
use PoPAPI\API\Constants\Actions;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Constants\DataOutputModes;

trait RemoveEntryModuleFromOutputEngineTrait
{
    protected function getEncodedDataObject(array $data): array
    {
        $data = parent::getEncodedDataObject($data);

        // For the API: maybe remove the entry module from the output
        if (
            App::getComponent(APIModule::class)->isEnabled() &&
            App::getState('scheme') === APISchemes::API &&
            in_array(Actions::REMOVE_ENTRYMODULE_FROM_OUTPUT, App::getState('actions')) &&
            App::getState('dataoutputmode') == DataOutputModes::COMBINED
        ) {
            if ($data['datasetmodulesettings'] ?? null) {
                $data['datasetmodulesettings'] = $this->removeEntryModuleFromOutput($data['datasetmodulesettings']);
            }
            if ($data['moduledata'] ?? null) {
                $data['moduledata'] = $this->removeEntryModuleFromOutput($data['moduledata']);
            }
            if ($data['datasetmoduledata'] ?? null) {
                $data['datasetmoduledata'] = $this->removeEntryModuleFromOutput($data['datasetmoduledata']);
            }
            if ($data['modulesettings'] ?? null) {
                $data['modulesettings'] = $this->removeEntryModuleFromOutput($data['modulesettings']);
            }
        }

        return $data;
    }

    protected function removeEntryModuleFromOutput(array $results): array
    {
        list($has_extra_routes) = $this->listExtraRouteVars();
        return $has_extra_routes ? array_values(array_values($results)[0])[0] : array_values($results)[0];
    }
}
