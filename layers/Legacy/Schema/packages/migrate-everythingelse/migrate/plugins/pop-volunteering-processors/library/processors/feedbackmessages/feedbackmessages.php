<?php

class PoP_Volunteering_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_VOLUNTEER = 'feedbackmessage-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_VOLUNTEER],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_VOLUNTEER => [PoP_Volunteering_Module_Processor_FeedbackMessageInners::class, PoP_Volunteering_Module_Processor_FeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_VOLUNTEER],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



