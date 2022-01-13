<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UpdateProfileFeedbackMessageLayouts extends PoP_Module_Processor_UpdateUserFormMesageFeedbackLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE = 'layout-feedbackmessage-updateprofile';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Profile updated successfully.', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



