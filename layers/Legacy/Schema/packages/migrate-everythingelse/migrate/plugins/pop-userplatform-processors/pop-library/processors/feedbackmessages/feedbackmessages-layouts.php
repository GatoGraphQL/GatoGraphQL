<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Core_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS = 'layout-feedbackmessage-inviteusers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Invite successful!', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



