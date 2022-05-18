<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_CUU_USERNAME = 'forminputgroup-cuu-username';
    public final const MODULE_FORMINPUTGROUP_CUU_EMAIL = 'forminputgroup-cuu-email';
    public final const MODULE_FORMINPUTGROUP_CUU_CURRENTPASSWORD = 'forminputgroup-cuu-currentpassword';
    public final const MODULE_FORMINPUTGROUP_CUU_PASSWORD = 'forminputgroup-cuu-password';
    public final const MODULE_FORMINPUTGROUP_CUU_NEWPASSWORD = 'forminputgroup-cuu-newpassword';
    public final const MODULE_FORMINPUTGROUP_CUU_PASSWORDREPEAT = 'forminputgroup-cuu-passwordrepeat';
    public final const MODULE_FORMINPUTGROUP_CUU_NEWPASSWORDREPEAT = 'forminputgroup-cuu-newpasswordrepeat';
    public final const MODULE_FORMINPUTGROUP_CUU_FIRSTNAME = 'forminputgroup-cuu-firstName';
    public final const MODULE_FORMINPUTGROUP_CUU_USERWEBSITEURL = 'forminputgroup-cuu-userwebsiteurl';
    public final const MODULE_FORMINPUTGROUP_CUU_DESCRIPTION = 'forminputgroup-cuu-description';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_USERNAME],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_EMAIL],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_CURRENTPASSWORD],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_PASSWORD],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_NEWPASSWORD],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_PASSWORDREPEAT],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_NEWPASSWORDREPEAT],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_FIRSTNAME],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_USERWEBSITEURL],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUU_DESCRIPTION],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        $ret = parent::getLabel($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_CUU_USERWEBSITEURL:
                $ret = '<i class="fa fa-fw fa-home"></i>'.$ret;
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_CUU_USERNAME => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_USERNAME],
            self::COMPONENT_FORMINPUTGROUP_CUU_EMAIL => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_EMAIL],
            self::COMPONENT_FORMINPUTGROUP_CUU_CURRENTPASSWORD => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_CURRENTPASSWORD],
            self::COMPONENT_FORMINPUTGROUP_CUU_PASSWORD => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_PASSWORD],
            self::COMPONENT_FORMINPUTGROUP_CUU_NEWPASSWORD => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_NEWPASSWORD],
            self::COMPONENT_FORMINPUTGROUP_CUU_PASSWORDREPEAT => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_PASSWORDREPEAT],
            self::COMPONENT_FORMINPUTGROUP_CUU_NEWPASSWORDREPEAT => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_NEWPASSWORDREPEAT],
            self::COMPONENT_FORMINPUTGROUP_CUU_FIRSTNAME => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_FIRSTNAME],
            self::COMPONENT_FORMINPUTGROUP_CUU_USERWEBSITEURL => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_USERWEBSITEURL],
            self::COMPONENT_FORMINPUTGROUP_CUU_DESCRIPTION => [PoP_Module_Processor_CreateUpdateUserTextareaFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextareaFormInputs::COMPONENT_FORMINPUT_CUU_DESCRIPTION],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function getInfo(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_CUU_EMAIL:
                return TranslationAPIFacade::getInstance()->__('Please be careful to fill-in the email properly! It will not only appear in the profile page, but also be used by our system to send you notifications.', 'pop-coreprocessors');
        }

        return parent::getInfo($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Override the placeholders
        $placeholders = array(
            self::COMPONENT_FORMINPUTGROUP_CUU_USERWEBSITEURL => 'https://...',
        );
        if ($placeholder = $placeholders[$component[1]] ?? null) {
            $component = $this->getComponentSubmodule($component);
            $this->setProp($component, $props, 'placeholder', $placeholder);
        }

        parent::initModelProps($component, $props);
    }
}



