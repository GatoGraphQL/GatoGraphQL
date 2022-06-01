<?php

class PoP_Module_Processor_UserFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_MYPREFERENCES = 'feedbackmessage-mypreferences';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_MYPREFERENCES],
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_MYPREFERENCES => [PoP_Module_Processor_UserFeedbackMessageInners::class, PoP_Module_Processor_UserFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_MYPREFERENCES],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



