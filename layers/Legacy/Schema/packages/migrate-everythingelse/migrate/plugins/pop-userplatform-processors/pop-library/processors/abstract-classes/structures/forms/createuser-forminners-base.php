<?php

abstract class PoP_Module_Processor_CreateUserFormInnersBase extends PoP_Module_Processor_FormInnersBase
{
    public function getLayoutSubmodules(array $module)
    {
        $components =  array_merge(
            parent::getLayoutSubmodules($module),
            array(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_USERNAME],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_PASSWORD],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_PASSWORDREPEAT],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::MODULE_DIVIDER],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_FIRSTNAME],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_EMAIL],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::MODULE_COLLAPSIBLEDIVIDER],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_DESCRIPTION],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::MODULE_COLLAPSIBLEDIVIDER],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_USERWEBSITEURL],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::MODULE_DIVIDER],
                [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SUBMIT],
            )
        );
        if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
            if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                array_splice(
                    $components,
                    array_search(
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SUBMIT],
                        $components
                    ),
                    0,
                    array(
                        [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_CAPTCHA],
                    )
                );
            }
        }

        // Hook for User Avatar
        $components = \PoP\Root\App::getHookManager()->applyFilters('pop_module:createuser:components', $components, $module, $this);

        return $components;
    }

    protected function getMandatoryLayouts(array $module, array &$props)
    {

        // Make all formComponentGroups be collapsed if they are non-mandatory
        $mandatory = array(
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_USERNAME],
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_PASSWORD],
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_PASSWORDREPEAT],
            [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::MODULE_DIVIDER],
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_FIRSTNAME],
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_EMAIL],
            [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SUBMIT],
        );
        if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
            if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                $mandatory[] = [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_CAPTCHA];
            }
        }

        return \PoP\Root\App::getHookManager()->applyFilters(
            'pop_module:createuser:mandatory-components',
            $mandatory,
            $module
        );
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Make all formComponentGroups be collapsed if they are non-mandatory
        $collapsible = array_values(
            array_diff(
                $this->getLayoutSubmodules($module),
                $this->getMandatoryLayouts($module, $props)
            )
        );
        foreach ($collapsible as $layout) {
            // Also add class "pop-highlight" so there is some flashing on the extra fields for the user to see
            $this->appendProp($layout, $props, 'class', 'collapse pop-highlight');
        }

        parent::initModelProps($module, $props);
    }
}
