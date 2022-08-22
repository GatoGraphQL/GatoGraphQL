<?php

class PoP_Module_Processor_UserFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_INVITENEWUSERS = 'forminner-inviteusers';
    public final const COMPONENT_FORMINNER_MYPREFERENCES = 'forminner-mypreferences';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_INVITENEWUSERS,
            self::COMPONENT_FORMINNER_MYPREFERENCES,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_INVITENEWUSERS:
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_EMAILS];
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_SENDERNAME];
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_ADDITIONALMESSAGE];
                if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
                    if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                        $ret[] = [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_CAPTCHA];
                    }
                }
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SEND];
                break;

            case self::COMPONENT_FORMINNER_MYPREFERENCES:
                $ret = array_merge(
                    array(
                        [PoP_Module_Processor_UserMultipleComponents::class, PoP_Module_Processor_UserMultipleComponents::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS],
                        [PoP_Module_Processor_UserMultipleComponents::class, PoP_Module_Processor_UserMultipleComponents::COMPONENT_MULTICOMPONENT_EMAILDIGESTS],
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SAVE],
                    )
                );
                break;
        }

        return $ret;
    }
}



