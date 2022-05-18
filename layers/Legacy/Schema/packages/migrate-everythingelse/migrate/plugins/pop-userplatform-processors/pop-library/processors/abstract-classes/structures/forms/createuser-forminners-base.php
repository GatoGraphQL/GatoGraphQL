<?php

abstract class PoP_Module_Processor_CreateUserFormInnersBase extends PoP_Module_Processor_FormInnersBase
{
    public function getLayoutSubmodules(array $component)
    {
        $components =  array_merge(
            parent::getLayoutSubmodules($component),
            array(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_USERNAME],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_PASSWORD],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_PASSWORDREPEAT],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_DIVIDER],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_FIRSTNAME],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_EMAIL],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_COLLAPSIBLEDIVIDER],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_DESCRIPTION],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_COLLAPSIBLEDIVIDER],
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_USERWEBSITEURL],
                [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_DIVIDER],
                [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SUBMIT],
            )
        );
        if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
            if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                array_splice(
                    $components,
                    array_search(
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SUBMIT],
                        $components
                    ),
                    0,
                    array(
                        [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_CAPTCHA],
                    )
                );
            }
        }

        // Hook for User Avatar
        $components = \PoP\Root\App::applyFilters('pop_component:createuser:components', $components, $component, $this);

        return $components;
    }

    protected function getMandatoryLayouts(array $component, array &$props)
    {

        // Make all formComponentGroups be collapsed if they are non-mandatory
        $mandatory = array(
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_USERNAME],
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_PASSWORD],
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_PASSWORDREPEAT],
            [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_DIVIDER],
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_FIRSTNAME],
            [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_EMAIL],
            [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SUBMIT],
        );
        if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
            if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                $mandatory[] = [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_CAPTCHA];
            }
        }

        return \PoP\Root\App::applyFilters(
            'pop_component:createuser:mandatory-components',
            $mandatory,
            $component
        );
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Make all formComponentGroups be collapsed if they are non-mandatory
        $collapsible = array_values(
            array_diff(
                $this->getLayoutSubmodules($component),
                $this->getMandatoryLayouts($component, $props)
            )
        );
        foreach ($collapsible as $layout) {
            // Also add class "pop-highlight" so there is some flashing on the extra fields for the user to see
            $this->appendProp($layout, $props, 'class', 'collapse pop-highlight');
        }

        parent::initModelProps($component, $props);
    }
}
