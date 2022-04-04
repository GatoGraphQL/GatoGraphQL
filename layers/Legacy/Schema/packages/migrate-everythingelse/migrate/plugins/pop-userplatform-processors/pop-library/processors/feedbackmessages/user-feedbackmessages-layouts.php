<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_MYPREFERENCES = 'layout-feedbackmessage-mypreferences';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_MYPREFERENCES],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_MYPREFERENCES:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Preferences saved.', 'pop-coreprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('You have successfully updated your preferences.', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



