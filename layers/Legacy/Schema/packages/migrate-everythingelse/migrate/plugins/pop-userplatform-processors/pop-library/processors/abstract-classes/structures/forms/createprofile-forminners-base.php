<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

abstract class PoP_Module_Processor_CreateProfileFormInnersBase extends PoP_Module_Processor_CreateUserFormInnersBase
{
    public function getLayoutSubmodules(array $module)
    {
        $components = parent::getLayoutSubmodules($module);

        // Add common Create/Update components
        PoP_Module_Processor_CreateUpdateProfileFormsUtils::getFormSubmodules($module, $components, $this);

        // Hook for Newsletter
        $components = \PoP\Root\App::getHookManager()->applyFilters('pop_module:createprofile:components', $components, $module, $this);

        return $components;
    }

    protected function getMandatoryLayouts(array $module, array &$props)
    {
        $ret = parent::getMandatoryLayouts($module, $props);
        $ret[] = [PoP_Module_Processor_NoLabelProfileFormGroups::class, PoP_Module_Processor_NoLabelProfileFormGroups::MODULE_FORMINPUTGROUP_CUP_DISPLAYEMAIL];
        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // For the Creation, set the Display Email by default on Yes
        $this->setProp([PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs::MODULE_FORMINPUT_CUP_DISPLAYEMAIL], $props, 'default-value', true);
        parent::initModelProps($module, $props);
    }
}
