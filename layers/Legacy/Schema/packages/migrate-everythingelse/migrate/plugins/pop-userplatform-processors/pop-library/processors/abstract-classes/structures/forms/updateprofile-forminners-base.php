<?php

abstract class PoP_Module_Processor_UpdateProfileFormInnersBase extends PoP_Module_Processor_UpdateUserFormInnersBase
{
    public function getLayoutSubmodules(array $component)
    {
        $components = parent::getLayoutSubmodules($component);
    
        // Add common Create/Update components
        PoP_Module_Processor_CreateUpdateProfileFormsUtils::getFormSubmodules($component, $components, $this);

        // Hook for Newsletter
        $components = \PoP\Root\App::applyFilters('pop_component:updateprofile:components', $components, $component, $this);
        
        return $components;
    }
}
