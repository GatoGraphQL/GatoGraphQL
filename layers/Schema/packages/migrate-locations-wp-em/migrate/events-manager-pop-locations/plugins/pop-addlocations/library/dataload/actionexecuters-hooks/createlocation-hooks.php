<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class EM_PoPLocations_DataLoad_ActionExecuter_CreateLocationHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'create_location',
            array($this, 'renameFields')
        );
    }

    public function renameFields()
    {
        $module_inputnames = array(
            [
                'module' => [GD_EM_Module_Processor_CreateLocationSelectFormInputs::class, GD_EM_Module_Processor_CreateLocationSelectFormInputs::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY],
                'inputName' => 'location_country',
            ],
            [
                'module' => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONLAT],
                'inputName' => 'location_latitude',
            ],
            [
                'module' => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONLNG],
                'inputName' => 'location_longitude',
            ],
            [
                'module' => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONNAME],
                'inputName' => 'location_name',
            ],
            [
                'module' => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONADDRESS],
                'inputName' => 'location_address',
            ],
            [
                'module' => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONTOWN],
                'inputName' => 'location_town',
            ],
            [
                'module' => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONSTATE],
                'inputName' => 'location_state',
            ],
            [
                'module' => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONPOSTCODE],
                'inputName' => 'location_postcode',
            ],
            [
                'module' => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONREGION],
                'inputName' => 'location_region',
            ],
        );

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        foreach ($module_inputnames as $module_inputname) {
            // For each regular PoP module value, set it also under the expected form input name by Events Manager
            $module = $module_inputname['module'];
            $value = $moduleprocessor_manager->getProcessor($module)->getValue($module);
            if (!is_null($value)) {
                // Also trim it, to avoid whitespaces in the location name
                $em_form_fieldname = $module_inputname['inputName'];
                $_POST[$em_form_fieldname] = trim($value);
            }
        }
    }
}
    
/**
 * Initialize
 */
new EM_PoPLocations_DataLoad_ActionExecuter_CreateLocationHooks();
