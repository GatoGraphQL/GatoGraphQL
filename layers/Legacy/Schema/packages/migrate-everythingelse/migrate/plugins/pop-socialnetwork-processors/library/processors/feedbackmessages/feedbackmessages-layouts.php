<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_CONTACTUSER = 'layout-feedbackmessage-contactuser';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CONTACTUSER],
        );
    }

    public function getMessages(array $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CONTACTUSER:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Message sent successfully!', 'pop-genericforms');
                $ret['success'] = TranslationAPIFacade::getInstance()->__("Your message has been sent to the user by email.", 'pop-genericforms');
                $ret['empty-name'] = TranslationAPIFacade::getInstance()->__('Name is missing.', 'pop-genericforms');
                $ret['empty-email'] = TranslationAPIFacade::getInstance()->__('Email is missing or format is incorrect.', 'pop-genericforms');
                $ret['empty-message'] = TranslationAPIFacade::getInstance()->__('Message is missing.', 'pop-genericforms');
                break;
        }

        return $ret;
    }
}



