<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_MYPREFERENCES = 'layout-feedbackmessage-mypreferences';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_MYPREFERENCES],
        );
    }

    public function getMessages(array $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_MYPREFERENCES:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Preferences saved.', 'pop-coreprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('You have successfully updated your preferences.', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



