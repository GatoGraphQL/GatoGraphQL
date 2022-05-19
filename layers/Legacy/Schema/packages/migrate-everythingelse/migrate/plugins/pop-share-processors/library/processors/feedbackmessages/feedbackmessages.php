<?php

class PoP_Share_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_SHAREBYEMAIL = 'feedbackmessage-sharebyemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_SHAREBYEMAIL],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_SHAREBYEMAIL => [PoP_Share_Module_Processor_FeedbackMessageInners::class, PoP_Share_Module_Processor_FeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_SHAREBYEMAIL],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



