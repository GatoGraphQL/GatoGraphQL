<?php

class PoP_SocialNetwork_Module_Processor_GFFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_CONTACTUSER = 'forminner-contactuser';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_CONTACTUSER],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_CONTACTUSER:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_FormComponentGroups::class, PoP_SocialNetwork_Module_Processor_FormComponentGroups::COMPONENT_FORMCOMPONENTGROUP_CARD_CONTACTUSER],
                        [PoP_Forms_Module_Processor_FormGroups::class, PoP_Forms_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_NAME],
                        [PoP_Forms_Module_Processor_FormGroups::class, PoP_Forms_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_EMAIL],
                        [PoP_SocialNetwork_Module_Processor_FormGroups::class, PoP_SocialNetwork_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_MESSAGESUBJECT],
                        [PoP_SocialNetwork_Module_Processor_FormGroups::class, PoP_SocialNetwork_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_MESSAGETOUSER],
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETURL],
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_USERNICENAME],
                        [PoP_SocialNetwork_Module_Processor_SubmitButtons::class, PoP_SocialNetwork_Module_Processor_SubmitButtons::COMPONENT_GF_SUBMITBUTTON_SENDMESSAGETOUSER],
                    )
                );
                if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
                    if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                        array_splice(
                            $ret,
                            array_search(
                                [PoP_SocialNetwork_Module_Processor_SubmitButtons::class, PoP_SocialNetwork_Module_Processor_SubmitButtons::COMPONENT_GF_SUBMITBUTTON_SENDMESSAGETOUSER],
                                $ret
                            ),
                            0,
                            array(
                                [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_CAPTCHA],
                            )
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



