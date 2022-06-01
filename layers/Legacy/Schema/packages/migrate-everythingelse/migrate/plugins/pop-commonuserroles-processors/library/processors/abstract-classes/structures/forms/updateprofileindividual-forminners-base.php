<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Module_Processor_UpdateProfileIndividualFormInnersBase extends PoP_Module_Processor_UpdateProfileFormInnersBase
{
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        // Add common Create/Update components
        PoP_Module_Processor_CreateUpdateProfileIndividualFormsUtils::getFormSubcomponents($component, $ret, $this);

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Change the title for the Individual Description
        $this->setProp([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_DESCRIPTION], $props, 'label', TranslationAPIFacade::getInstance()->__('Tell us about yourself', 'ure-popprocessors'));
        $this->setProp([PoP_Module_Processor_CreateUpdateUserTextareaFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextareaFormInputs::COMPONENT_FORMINPUT_CUU_DESCRIPTION], $props, 'placeholder', TranslationAPIFacade::getInstance()->__('How cool are you?', 'ure-popprocessors'));
        parent::initModelProps($component, $props);
    }
}
