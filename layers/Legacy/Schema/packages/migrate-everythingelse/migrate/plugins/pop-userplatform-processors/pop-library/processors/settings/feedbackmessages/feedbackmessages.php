<?php

class PoP_Module_Processor_SettingsFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_SETTINGS = 'feedbackmessage-settings';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_SETTINGS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_SETTINGS => [PoP_Module_Processor_SettingsFeedbackMessageInners::class, PoP_Module_Processor_SettingsFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_SETTINGS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



