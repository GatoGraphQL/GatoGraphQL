<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_VOLUNTEER = 'layout-feedbackmessage-volunteer';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_VOLUNTEER,
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_VOLUNTEER:
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



