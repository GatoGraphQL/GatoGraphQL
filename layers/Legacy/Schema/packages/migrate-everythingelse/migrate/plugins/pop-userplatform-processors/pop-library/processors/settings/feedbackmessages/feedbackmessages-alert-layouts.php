<?php

class PoP_Module_Processor_SettingsFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_SETTINGS = 'layout-feedbackmessagealert-settings';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_SETTINGS,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_SETTINGS => [PoP_Module_Processor_SettingsFeedbackMessageLayouts::class, PoP_Module_Processor_SettingsFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_SETTINGS],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



