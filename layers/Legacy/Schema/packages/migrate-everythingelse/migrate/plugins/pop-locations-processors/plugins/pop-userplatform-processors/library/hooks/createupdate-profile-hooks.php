<?php

class GD_EM_CreateUpdate_Profile_Hooks
{
    public function __construct()
    {

        // Hooks for PoP Locations
        // Because it is placed under plugins/pop-userplatform-processors/, the dataload-saving logic only takes effect if the corresponding processors on which to add the Typeahead Map exist
        \PoP\Root\App::addFilter('PoP_Location_UserPlatform_ProfileHooks:form-input', $this->getLocationsForminputInput(...));

        // Processor Hooks
        \PoP\Root\App::addFilter('pop_component:createprofile:components', $this->getComponentSubcomponents(...), 10, 3);
        \PoP\Root\App::addFilter('pop_component:updateprofile:components', $this->getComponentSubcomponents(...), 10, 3);
    }

    public function getLocationsForminputInput()
    {
        return [PoP_Module_Processor_SelectableTypeaheadMapFormComponents::class, PoP_Module_Processor_SelectableTypeaheadMapFormComponents::COMPONENT_EM_FORMCOMPONENT_TYPEAHEADMAP];
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getComponentSubcomponents(array $components, \PoP\ComponentModel\Component\Component $component, $processor): array
    {
        // Add before the Captcha
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_DESCRIPTION], 
                $components
            )+1, 
            0, 
            array(
                [GD_EM_Module_Processor_FormComponentGroups::class, GD_EM_Module_Processor_FormComponentGroups::COMPONENT_EM_FORMCOMPONENTGROUP_TYPEAHEADMAP],
            )
        );
        
        return $components;
    }
}

/**
 * Initialize
 */
new GD_EM_CreateUpdate_Profile_Hooks();
