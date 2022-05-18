<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_VOLUNTEER = 'layout-feedbackmessage-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_VOLUNTEER],
        );
    }

    public function getMessages(array $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_VOLUNTEER:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Awesome! Thanks for taking part!', 'pop-genericforms');
                $ret['success'] = TranslationAPIFacade::getInstance()->__("We have sent a message with your information to the organizers. They should contact you soon.", 'pop-genericforms');
                $ret['empty-name'] = TranslationAPIFacade::getInstance()->__('Name is missing.', 'pop-genericforms');
                $ret['empty-email'] = TranslationAPIFacade::getInstance()->__('Email is missing or format is incorrect.', 'pop-genericforms');
                $ret['empty-whyvolunteer'] = TranslationAPIFacade::getInstance()->__("\"Why do you want to volunteer?\" is missing.", 'pop-genericforms');
                break;
        }

        return $ret;
    }
}



