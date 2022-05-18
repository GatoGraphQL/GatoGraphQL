<?php

class PoP_Module_Processor_ProfileFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_CREATEPROFILE = 'feedbackmessage-createprofile';
    public final const COMPONENT_FEEDBACKMESSAGE_UPDATEPROFILE = 'feedbackmessage-updateprofile';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_CREATEPROFILE],
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_UPDATEPROFILE],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_CREATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageInners::class, PoP_Module_Processor_ProfileFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_CREATEPROFILE],
            self::COMPONENT_FEEDBACKMESSAGE_UPDATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageInners::class, PoP_Module_Processor_ProfileFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_UPDATEPROFILE],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



