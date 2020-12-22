<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class PoP_Location_UserPlatform_ProfileHooks
{
    public function __construct()
    {

        // ActionExecuterBase Hooks
        HooksAPIFacade::getInstance()->addFilter('gd_createupdate_profile:form_data', array($this, 'getFormData'));
        HooksAPIFacade::getInstance()->addAction('gd_createupdate_profile:additionals', array($this, 'additionals'), 10, 2);
    }

    private function getLocationsForminputInput()
    {

        // This hooks will be implemented by PoP Locations Processors
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_Location_UserPlatform_ProfileHooks:form-input',
            null
        );
    }

    public function getFormData($form_data)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($locations_input = $this->getLocationsForminputInput()) {
            $form_data['locations'] = $moduleprocessor_manager->getProcessor($locations_input)->getValue($locations_input);
        }
        
        return $form_data;
    }

    public function additionals($user_id, $form_data)
    {
        if ($this->getLocationsForminputInput()) {
            \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LOCATIONS, $form_data['locations']);
        }
    }
}

/**
 * Initialize
 */
new PoP_Location_UserPlatform_ProfileHooks();
