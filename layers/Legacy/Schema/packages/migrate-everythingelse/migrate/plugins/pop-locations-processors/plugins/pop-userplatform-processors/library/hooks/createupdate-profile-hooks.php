<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class GD_EM_CreateUpdate_Profile_Hooks
{
    public function __construct()
    {

        // Hooks for PoP Locations
        // Because it is placed under plugins/pop-userplatform-processors/, the dataload-saving logic only takes effect if the corresponding processors on which to add the Typeahead Map exist
        HooksAPIFacade::getInstance()->addFilter('PoP_Location_UserPlatform_ProfileHooks:form-input', array($this, 'getLocationsForminputInput'));

        // Processor Hooks
        HooksAPIFacade::getInstance()->addFilter('pop_module:createprofile:components', array($this, 'getComponentSubmodules'), 10, 3);
        HooksAPIFacade::getInstance()->addFilter('pop_module:updateprofile:components', array($this, 'getComponentSubmodules'), 10, 3);
    }

    public function getLocationsForminputInput()
    {
        return [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::MODULE_EM_FORMCOMPONENT_TYPEAHEADMAP];
    }

    public function getComponentSubmodules($components, array $module, $processor)
    {

        // Add before the Captcha
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_DESCRIPTION], 
                $components
            )+1, 
            0, 
            array(
                [GD_EM_Module_Processor_FormComponentGroups::class, GD_EM_Module_Processor_FormComponentGroups::MODULE_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP],
            )
        );
        
        return $components;
    }
}

/**
 * Initialize
 */
new GD_EM_CreateUpdate_Profile_Hooks();
