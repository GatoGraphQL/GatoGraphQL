<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContactUs_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_CONTACTUS = 'layout-feedbackmessage-contactus';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CONTACTUS,
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CONTACTUS:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Message sent successfully!', 'pop-genericforms');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Thanks for contacting us, we will get in touch with you shortly.', 'pop-genericforms');
                $ret['empty-name'] = TranslationAPIFacade::getInstance()->__('Name is missing.', 'pop-genericforms');
                $ret['empty-email'] = TranslationAPIFacade::getInstance()->__('Email is missing or format is incorrect.', 'pop-genericforms');
                $ret['empty-message'] = TranslationAPIFacade::getInstance()->__('Message is missing.', 'pop-genericforms');
                break;
        }

        return $ret;
    }
}



