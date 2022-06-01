<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_USERAVATAR_UPDATE = 'layout-feedbackmessage-useravatar-update';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_USERAVATAR_UPDATE,
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_USERAVATAR_UPDATE:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Picture updated successfully.', 'pop-useravatar-processors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Such a good shot!', 'pop-useravatar-processors');
                break;
        }

        return $ret;
    }
}



