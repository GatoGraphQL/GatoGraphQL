<?php

abstract class PoP_Module_Processor_UpdateProfileFormInnersBase extends PoP_Module_Processor_UpdateUserFormInnersBase
{
    public function getLayoutSubmodules(array $module)
    {
        $components = parent::getLayoutSubmodules($module);
    
        // Add common Create/Update components
        PoP_Module_Processor_CreateUpdateProfileFormsUtils::getFormSubmodules($module, $components, $this);

        // Hook for Newsletter
        $components = \PoP\Root\App::applyFilters('pop_module:updateprofile:components', $components, $module, $this);
        
        return $components;
    }
}
