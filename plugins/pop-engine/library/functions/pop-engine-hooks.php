<?php
namespace PoP\Engine\Impl;

class EngineHooks
{
    public function __construct()
    {
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
            '\PoP\Engine\Engine:getModuleData:start',
            array($this, 'start'),
            10,
            4
        );
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
            '\PoP\Engine\Engine:getModuleData:dataloading-module',
            array($this, 'calculateDataloadingModuleData'),
            10,
            8
        );
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
            '\PoP\Engine\Engine:getModuleData:end',
            array($this, 'end'),
            10,
            5
        );
    }

    public function start($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array)
    {
        $helperCalculations = &$helperCalculations_in_array[0];
        $helperCalculations['has-lazy-load'] = false;
    }

    public function calculateDataloadingModuleData($module, $module_props_in_array, $data_properties_in_array, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids, $helperCalculations_in_array)
    {
        $data_properties = &$data_properties_in_array[0];

        if ($data_properties[GD_DATALOAD_LAZYLOAD]) {
            $helperCalculations = &$helperCalculations_in_array[0];
            $helperCalculations['has-lazy-load'] = true;
        }
    }

    public function end($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array, $engine)
    {
        $helperCalculations = &$helperCalculations_in_array[0];

        // Fetch the lazy-loaded data using the Background URL load
        if ($helperCalculations['has-lazy-load']) {
            $cmshelpers = \PoP\CMS\HelperAPI_Factory::getInstance();
            $url = $cmshelpers->addQueryArgs([
                GD_URLPARAM_DATAOUTPUTITEMS => [
                    GD_URLPARAM_DATAOUTPUTITEMS_META, 
                    GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, 
                    GD_URLPARAM_DATAOUTPUTITEMS_DATABASES,
                ],
                GD_URLPARAM_MODULEFILTER => POP_MODULEFILTER_LAZY,
                GD_URLPARAM_ACTION => POP_ACTION_LOADLAZY,
            ], \PoP\Engine\Utils::getCurrentUrl());
            $engine->addBackgroundUrl($url, array(POP_TARGET_MAIN));
        }
    }
}


/**
 * Initialization
 */
new EngineHooks();
