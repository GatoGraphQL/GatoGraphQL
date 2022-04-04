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

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_USERNAME],
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_EMAIL],
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_CURRENTPASSWORD],
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_PASSWORD],
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_NEWPASSWORD],
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_PASSWORDREPEAT],
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_NEWPASSWORDREPEAT],
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_FIRSTNAME],
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_USERWEBSITEURL],
            [self::class, self::MODULE_FORMINPUTGROUP_CUU_DESCRIPTION],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        $ret = parent::getLabel($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_CUU_USERWEBSITEURL:
                $ret = '<i class="fa fa-fw fa-home"></i>'.$ret;
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_CUU_USERNAME => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_USERNAME],
            self::MODULE_FORMINPUTGROUP_CUU_EMAIL => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_EMAIL],
            self::MODULE_FORMINPUTGROUP_CUU_CURRENTPASSWORD => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_CURRENTPASSWORD],
            self::MODULE_FORMINPUTGROUP_CUU_PASSWORD => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_PASSWORD],
            self::MODULE_FORMINPUTGROUP_CUU_NEWPASSWORD => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORD],
            self::MODULE_FORMINPUTGROUP_CUU_PASSWORDREPEAT => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_PASSWORDREPEAT],
            self::MODULE_FORMINPUTGROUP_CUU_NEWPASSWORDREPEAT => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_NEWPASSWORDREPEAT],
            self::MODULE_FORMINPUTGROUP_CUU_FIRSTNAME => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_FIRSTNAME],
            self::MODULE_FORMINPUTGROUP_CUU_USERWEBSITEURL => [PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::MODULE_FORMINPUT_CUU_USERWEBSITEURL],
            self::MODULE_FORMINPUTGROUP_CUU_DESCRIPTION => [PoP_Module_Processor_CreateUpdateUserTextareaFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextareaFormInputs::MODULE_FORMINPUT_CUU_DESCRIPTION],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_CUU_EMAIL:
                return TranslationAPIFacade::getInstance()->__('Please be careful to fill-in the email properly! It will not only appear in the profile page, but also be used by our system to send you notifications.', 'pop-coreprocessors');
        }

        return parent::getInfo($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Override the placeholders
        $placeholders = array(
            self::MODULE_FORMINPUTGROUP_CUU_USERWEBSITEURL => 'https://...',
        );
        if ($placeholder = $placeholders[$module[1]] ?? null) {
            $component = $this->getComponentSubmodule($module);
            $this->setProp($component, $props, 'placeholder', $placeholder);
        }

        parent::initModelProps($module, $props);
    }
}



