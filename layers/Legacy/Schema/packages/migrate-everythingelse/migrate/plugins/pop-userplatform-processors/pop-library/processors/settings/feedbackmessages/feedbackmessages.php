<?php

class PoP_Module_Processor_SettingsFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_SETTINGS = 'feedbackmessage-settings';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGE_SETTINGS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_SETTINGS => [PoP_Module_Processor_SettingsFeedbackMessageInners::class, PoP_Module_Processor_SettingsFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_SETTINGS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



