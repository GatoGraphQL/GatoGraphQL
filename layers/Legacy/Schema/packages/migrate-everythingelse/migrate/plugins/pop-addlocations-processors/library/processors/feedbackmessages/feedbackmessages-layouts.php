<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateLocationFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION = 'layout-feedbackmessage-createlocation';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION,
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Location added successfully!', 'em-popprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('More locations to add?', 'em-popprocessors');
                $ret['empty-town'] = TranslationAPIFacade::getInstance()->__('City is missing.', 'em-popprocessors');
                break;
        }

        return $ret;
    }
}



