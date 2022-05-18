<?php

class PoP_Share_Module_Processor_GFFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_SHAREBYEMAIL = 'forminner-sharebyemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_SHAREBYEMAIL],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_SHAREBYEMAIL:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Forms_Module_Processor_FormGroups::class, PoP_Forms_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_NAME],
                        [PoP_Share_Module_Processor_FormGroups::class, PoP_Share_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_DESTINATIONEMAIL],
                        [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_ADDITIONALMESSAGE],
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETURL],
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETTITLE],
                        [PoP_Share_Module_Processor_SubmitButtons::class, PoP_Share_Module_Processor_SubmitButtons::COMPONENT_GF_SUBMITBUTTON_SENDEMAIL],
                    )
                );
                if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
                    if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                        array_splice(
                            $ret,
                            array_search(
                                [PoP_Share_Module_Processor_SubmitButtons::class, PoP_Share_Module_Processor_SubmitButtons::COMPONENT_GF_SUBMITBUTTON_SENDEMAIL],
                                $ret
                            ),
                            0,
                            [
                                [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_CAPTCHA],
                            ]
                        );
                    }
                }
                break;
        }

        // Allow Gravity Forms to add extra fields
        $ret = \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_GFFormInners:layouts',
            $ret,
            $component
        );

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Allow Gravity Forms to set props on its added fields
        \PoP\Root\App::doAction(
            'PoP_Module_Processor_GFFormInners:init-props',
            $component,
            array(&$props),
            $this
        );

        parent::initModelProps($component, $props);
    }
}



