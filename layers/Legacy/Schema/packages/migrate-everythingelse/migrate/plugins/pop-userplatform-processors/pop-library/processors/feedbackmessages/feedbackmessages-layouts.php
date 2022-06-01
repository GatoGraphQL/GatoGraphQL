<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Core_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS = 'layout-feedbackmessage-inviteusers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS],
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_INVITENEWUSERS:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Invite successful!', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



