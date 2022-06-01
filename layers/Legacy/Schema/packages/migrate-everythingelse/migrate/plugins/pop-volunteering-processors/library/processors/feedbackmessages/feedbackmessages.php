<?php

class PoP_Volunteering_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_VOLUNTEER = 'feedbackmessage-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_VOLUNTEER],
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_VOLUNTEER => [PoP_Volunteering_Module_Processor_FeedbackMessageInners::class, PoP_Volunteering_Module_Processor_FeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_VOLUNTEER],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



