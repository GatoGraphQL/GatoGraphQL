<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Core_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS = 'layout-feedbackmessage-inviteusers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS],
        );
    }

    public function getMessages(array $componentVariation, array &$props)
    {
        $ret = parent::getMessages($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Invite successful!', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



