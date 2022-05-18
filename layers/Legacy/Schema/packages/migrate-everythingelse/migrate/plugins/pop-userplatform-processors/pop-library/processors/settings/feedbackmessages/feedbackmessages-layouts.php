<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_SettingsFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_SETTINGS = 'layout-feedbackmessage-settings';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_SETTINGS],
        );
    }

    public function getMessages(array $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_SETTINGS:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Alright, everything set up', 'poptheme-wassup');
                $ret['success'] = sprintf(
                    '%s %s',
                    GD_CONSTANT_LOADING_SPINNER,
                    TranslationAPIFacade::getInstance()->__('Redirecting, please wait...', 'poptheme-wassup')
                );
                break;
        }

        return $ret;
    }
}



