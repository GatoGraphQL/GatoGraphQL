<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UpdateProfileFeedbackMessageLayouts extends PoP_Module_Processor_UpdateUserFormMesageFeedbackLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE = 'layout-feedbackmessage-updateprofile';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE],
        );
    }

    public function getMessages(array $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Profile updated successfully.', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



