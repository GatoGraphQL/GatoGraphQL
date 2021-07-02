<?php

class PoP_Module_Processor_UserFormInners extends PoP_Module_Processor_FormInnersBase
{
    public const MODULE_FORMINNER_INVITENEWUSERS = 'forminner-inviteusers';
    public const MODULE_FORMINNER_MYPREFERENCES = 'forminner-mypreferences';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_INVITENEWUSERS],
            [self::class, self::MODULE_FORMINNER_MYPREFERENCES],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_FORMINNER_INVITENEWUSERS:
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_EMAILS];
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_SENDERNAME];
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_ADDITIONALMESSAGE];
                if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
                    if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                        $ret[] = [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_CAPTCHA];
                    }
                }
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SEND];
                break;

            case self::MODULE_FORMINNER_MYPREFERENCES:
                $ret = array_merge(
                    array(
                        [PoP_Module_Processor_UserMultipleComponents::class, PoP_Module_Processor_UserMultipleComponents::MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS],
                        [PoP_Module_Processor_UserMultipleComponents::class, PoP_Module_Processor_UserMultipleComponents::MODULE_MULTICOMPONENT_EMAILDIGESTS],
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SAVE],
                    )
                );
                break;
        }

        return $ret;
    }
}



