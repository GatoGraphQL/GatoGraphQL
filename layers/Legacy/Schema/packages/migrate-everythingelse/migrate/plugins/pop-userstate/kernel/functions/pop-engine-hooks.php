<?php
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_UserState_EngineHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            '\PoP\ComponentModel\Engine:getModuleData:start',
            array($this, 'start'),
            10,
            4
        );
        HooksAPIFacade::getInstance()->addAction(
            '\PoP\ComponentModel\Engine:getModuleData:dataloading-module',
            array($this, 'calculateDataloadingModuleData'),
            10,
            8
        );
        HooksAPIFacade::getInstance()->addAction(
            '\PoP\ComponentModel\Engine:getModuleData:end',
            array($this, 'end'),
            10,
            5
        );
    }

    public function start($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array)
    {
        $helperCalculations = &$helperCalculations_in_array[0];
        $helperCalculations['has-userstatedata-load'] = false;
    }

    public function calculateDataloadingModuleData(array $module, $module_props_in_array, $data_properties_in_array, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs, $helperCalculations_in_array)
    {
        $data_properties = &$data_properties_in_array[0];

        if ($data_properties[GD_DATALOAD_USERSTATEDATALOAD]) {
            $helperCalculations = &$helperCalculations_in_array[0];
            $helperCalculations['has-userstatedata-load'] = true;
        }
    }

    public function end($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array, $engine)
    {
        $helperCalculations = &$helperCalculations_in_array[0];

        // Fetch the lazy-loaded data using the Background URL load
        if ($helperCalculations['has-userstatedata-load'] ?? null) {
            $requestHelperService = RequestHelperServiceFacade::getInstance();
            $url = GeneralUtils::addQueryArgs(
                [
                    Params::DATA_OUTPUT_ITEMS => [
                        DataOutputItems::META,
                        DataOutputItems::MODULE_DATA,
                        DataOutputItems::DATABASES,
                    ],
                    Params::MODULEFILTER => POP_MODULEFILTER_USERSTATE,
                    Params::ACTIONS.'[]' => POP_ACTION_LOADUSERSTATE,
                ],
                $requestHelperService->getCurrentURL()
            );
            $engine->addBackgroundUrl($url, array(\PoP\ConfigurationComponentModel\Constants\Targets::MAIN));
        }
    }
}


/**
 * Initialization
 */
new PoP_UserState_EngineHooks();
