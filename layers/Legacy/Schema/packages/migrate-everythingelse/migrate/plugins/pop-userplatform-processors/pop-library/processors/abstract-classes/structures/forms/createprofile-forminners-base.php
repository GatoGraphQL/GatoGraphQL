<?php

abstract class PoP_Module_Processor_CreateProfileFormInnersBase extends PoP_Module_Processor_CreateUserFormInnersBase
{
    public function getLayoutSubmodules(array $component)
    {
        $components = parent::getLayoutSubmodules($component);

        // Add common Create/Update components
        PoP_Module_Processor_CreateUpdateProfileFormsUtils::getFormSubmodules($component, $components, $this);

        // Hook for Newsletter
        $components = \PoP\Root\App::applyFilters('pop_component:createprofile:components', $components, $component, $this);

        return $components;
    }

    protected function getMandatoryLayouts(array $component, array &$props)
    {
        $ret = parent::getMandatoryLayouts($component, $props);
        $ret[] = [PoP_Module_Processor_NoLabelProfileFormGroups::class, PoP_Module_Processor_NoLabelProfileFormGroups::COMPONENT_FORMINPUTGROUP_CUP_DISPLAYEMAIL];
        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // For the Creation, set the Display Email by default on Yes
        $this->setProp([PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::COMPONENT_FORMINPUT_CUP_DISPLAYEMAIL], $props, 'default-value', true);
        parent::initModelProps($component, $props);
    }
}
