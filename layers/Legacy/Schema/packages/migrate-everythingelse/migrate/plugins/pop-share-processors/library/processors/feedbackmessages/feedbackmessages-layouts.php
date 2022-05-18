<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Share_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_SHAREBYEMAIL = 'layout-feedbackmessage-sharebyemail';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_SHAREBYEMAIL],
        );
    }

    public function getMessages(array $componentVariation, array &$props)
    {
        $ret = parent::getMessages($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_SHAREBYEMAIL:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Email sent successfully.', 'pop-genericforms');
                $ret['success'] = TranslationAPIFacade::getInstance()->__("Any more friends who might be interested? Keep sending!", 'pop-genericforms');
                $ret['empty-name'] = TranslationAPIFacade::getInstance()->__('Name is missing.', 'pop-genericforms');
                $ret['empty-destination-email'] = TranslationAPIFacade::getInstance()->__('To Email(s) missing.', 'pop-genericforms');
                break;
        }

        return $ret;
    }
}



