<?php

declare(strict_types=1);

namespace PoP\API\Engine;

use PoP\API\Component as APIComponent;
use PoP\ComponentModel\State\ApplicationState;
use PoP\API\Response\Schemes as APISchemes;

trait RemoveEntryModuleFromOutputEngineTrait
{

    protected function getEncodedDataObject($data)
    {
        $data = parent::getEncodedDataObject($data);

        // For the API: maybe remove the entry module from the output
        $vars = ApplicationState::getVars();
        if (APIComponent::isEnabled() &&
            $vars['scheme'] == APISchemes::API &&
            in_array(POP_ACTION_REMOVE_ENTRYMODULE_FROM_OUTPUT, $vars['actions']) &&
            $vars['dataoutputmode'] == GD_URLPARAM_DATAOUTPUTMODE_COMBINED
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
