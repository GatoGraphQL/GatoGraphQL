<?php

abstract class PoP_Module_Processor_UpdateProfileFormInnersBase extends PoP_Module_Processor_UpdateUserFormInnersBase
{
    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $components = parent::getLayoutSubcomponents($component);
    
        // Add common Create/Update components
        PoP_Module_Processor_CreateUpdateProfileFormsUtils::getFormSubcomponents($component, $components, $this);

        // Hook for Newsletter
        $components = \PoP\Root\App::applyFilters('pop_component:updateprofile:components', $components, $component, $this);
        
        return $components;
    }
}
