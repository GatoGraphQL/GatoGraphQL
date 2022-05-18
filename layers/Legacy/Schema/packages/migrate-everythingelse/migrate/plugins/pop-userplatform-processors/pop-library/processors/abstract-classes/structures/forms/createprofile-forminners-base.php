<?php

abstract class PoP_Module_Processor_CreateProfileFormInnersBase extends PoP_Module_Processor_CreateUserFormInnersBase
{
    public function getLayoutSubmodules(array $componentVariation)
    {
        $components = parent::getLayoutSubmodules($componentVariation);

        // Add common Create/Update components
        PoP_Module_Processor_CreateUpdateProfileFormsUtils::getFormSubmodules($componentVariation, $components, $this);

        // Hook for Newsletter
        $components = \PoP\Root\App::applyFilters('pop_componentVariation:createprofile:components', $components, $componentVariation, $this);

        return $components;
    }

    protected function getMandatoryLayouts(array $componentVariation, array &$props)
    {
        $ret = parent::getMandatoryLayouts($componentVariation, $props);
        $ret[] = [PoP_Module_Processor_NoLabelProfileFormGroups::class, PoP_Module_Processor_NoLabelProfileFormGroups::MODULE_FORMINPUTGROUP_CUP_DISPLAYEMAIL];
        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // For the Creation, set the Display Email by default on Yes
        $this->setProp([PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::MODULE_FORMINPUT_CUP_DISPLAYEMAIL], $props, 'default-value', true);
        parent::initModelProps($componentVariation, $props);
    }
}
