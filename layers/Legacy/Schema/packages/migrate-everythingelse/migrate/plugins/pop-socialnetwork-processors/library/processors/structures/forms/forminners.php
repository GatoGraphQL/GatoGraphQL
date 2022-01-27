<?php

class PoP_SocialNetwork_Module_Processor_GFFormInners extends PoP_Module_Processor_FormInnersBase
{
    public const MODULE_FORMINNER_CONTACTUSER = 'forminner-contactuser';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_CONTACTUSER],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_FORMINNER_CONTACTUSER:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_FormComponentGroups::class, PoP_SocialNetwork_Module_Processor_FormComponentGroups::MODULE_FORMCOMPONENTGROUP_CARD_CONTACTUSER],
                        [PoP_Forms_Module_Processor_FormGroups::class, PoP_Forms_Module_Processor_FormGroups::MODULE_FORMINPUTGROUP_NAME],
                        [PoP_Forms_Module_Processor_FormGroups::class, PoP_Forms_Module_Processor_FormGroups::MODULE_FORMINPUTGROUP_EMAIL],
                        [PoP_SocialNetwork_Module_Processor_FormGroups::class, PoP_SocialNetwork_Module_Processor_FormGroups::MODULE_FORMINPUTGROUP_MESSAGESUBJECT],
                        [PoP_SocialNetwork_Module_Processor_FormGroups::class, PoP_SocialNetwork_Module_Processor_FormGroups::MODULE_FORMINPUTGROUP_MESSAGETOUSER],
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_TARGETURL],
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_USERNICENAME],
                        [PoP_SocialNetwork_Module_Processor_SubmitButtons::class, PoP_SocialNetwork_Module_Processor_SubmitButtons::MODULE_GF_SUBMITBUTTON_SENDMESSAGETOUSER],
                    )
                );
                if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
                    if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                        array_splice(
                            $ret,
                            array_search(
                                [PoP_SocialNetwork_Module_Processor_SubmitButtons::class, PoP_SocialNetwork_Module_Processor_SubmitButtons::MODULE_GF_SUBMITBUTTON_SENDMESSAGETOUSER],
                                $ret
                            ),
                            0,
                            array(
                                [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_CAPTCHA],
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
            $module
        );

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Allow Gravity Forms to set props on its added fields
        \PoP\Root\App::doAction(
            'PoP_Module_Processor_GFFormInners:init-props',
            $module,
            array(&$props),
            $this
        );

        parent::initModelProps($module, $props);
    }
}



