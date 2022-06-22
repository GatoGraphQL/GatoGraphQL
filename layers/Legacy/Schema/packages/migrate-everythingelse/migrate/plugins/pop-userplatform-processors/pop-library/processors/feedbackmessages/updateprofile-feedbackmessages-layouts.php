<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UpdateProfileFeedbackMessageLayouts extends PoP_Module_Processor_UpdateUserFormMesageFeedbackLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE = 'layout-feedbackmessage-updateprofile';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE,
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEPROFILE:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Profile updated successfully.', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



