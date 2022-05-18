<?php

abstract class PoP_Module_Processor_UpdateProfileFormInnersBase extends PoP_Module_Processor_UpdateUserFormInnersBase
{
    public function getLayoutSubmodules(array $componentVariation)
    {
        $components = parent::getLayoutSubmodules($componentVariation);
    
        // Add common Create/Update components
        PoP_Module_Processor_CreateUpdateProfileFormsUtils::getFormSubmodules($componentVariation, $components, $this);

        // Hook for Newsletter
        $components = \PoP\Root\App::applyFilters('pop_componentVariation:updateprofile:components', $components, $componentVariation, $this);
        
        return $components;
    }
}
