<?php

declare(strict_types=1);

namespace PoPAPI\API\Engine;

use PoP\Root\App;
use PoPAPI\API\Module as APIModule;
use PoPAPI\API\Constants\Actions;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Constants\DataOutputModes;

trait RemoveEntryComponentFromOutputEngineTrait
{
    protected function getEncodedDataObject(array $data): array
    {
        $data = parent::getEncodedDataObject($data);

        // For the API: maybe remove the entry component from the output
        if (
            App::getModule(APIModule::class)->isEnabled() &&
            App::getState('scheme') === APISchemes::API &&
            in_array(Actions::REMOVE_ENTRYCOMPONENT_FROM_OUTPUT, App::getState('actions')) &&
            App::getState('dataoutputmode') == DataOutputModes::COMBINED
        ) {
            if ($data['datasetcomponentsettings'] ?? null) {
                $data['datasetcomponentsettings'] = $this->removeEntryComponentFromOutput($data['datasetcomponentsettings']);
            }
            if ($data['componentdata'] ?? null) {
                $data['componentdata'] = $this->removeEntryComponentFromOutput($data['componentdata']);
            }
            if ($data['datasetcomponentdata'] ?? null) {
                $data['datasetcomponentdata'] = $this->removeEntryComponentFromOutput($data['datasetcomponentdata']);
            }
            if ($data['componentsettings'] ?? null) {
                $data['componentsettings'] = $this->removeEntryComponentFromOutput($data['componentsettings']);
            }
        }

        return $data;
    }

    protected function removeEntryComponentFromOutput(array $results): array
    {
        list($has_extra_routes) = $this->listExtraRouteVars();
        return $has_extra_routes ? array_values(array_values($results)[0])[0] : array_values($results)[0];
    }
}
