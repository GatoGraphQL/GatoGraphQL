<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Module_Processor_CreateProfileIndividualFormInnersBase extends PoP_Module_Processor_CreateProfileFormInnersBase
{
    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        // Add common Create/Update components
        PoP_Module_Processor_CreatProfileFormsUtils::getFormSubmodules($module, $ret, $this);
        PoP_Module_Processor_CreateUpdateProfileIndividualFormsUtils::getFormSubmodules($module, $ret, $this);

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Change the title for the Individual Description
        $this->setProp([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_DESCRIPTION], $props, 'label', TranslationAPIFacade::getInstance()->__('Tell us about yourself', 'ure-popprocessors'));
        $this->setProp([PoP_Module_Processor_CreateUpdateUserTextareaFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextareaFormInputs::MODULE_FORMINPUT_CUU_DESCRIPTION], $props, 'placeholder', TranslationAPIFacade::getInstance()->__('How cool are you?', 'ure-popprocessors'));
        parent::initModelProps($module, $props);
    }
}
