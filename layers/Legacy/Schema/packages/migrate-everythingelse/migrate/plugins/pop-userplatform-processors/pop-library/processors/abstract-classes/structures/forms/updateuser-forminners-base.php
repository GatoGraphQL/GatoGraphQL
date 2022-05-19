<?php

abstract class PoP_Module_Processor_UpdateUserFormInnersBase extends PoP_Module_Processor_FormInnersBase
{
    public function getLayoutSubcomponents(array $component)
    {
        return array_merge(
            parent::getLayoutSubcomponents($component),
            array(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_USERNAME],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_DIVIDER],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_FIRSTNAME],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_EMAIL],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_DIVIDER],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_DESCRIPTION],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_DIVIDER],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_USERWEBSITEURL],
                [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_UPDATE],
            )
        );
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->setProp([PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_USERNAME], $props, 'readonly', true);
        parent::initModelProps($component, $props);
    }
}
