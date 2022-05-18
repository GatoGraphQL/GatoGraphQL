<?php

class PoP_Module_Processor_UserFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_MYPREFERENCES = 'feedbackmessage-mypreferences';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_MYPREFERENCES],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_MYPREFERENCES => [PoP_Module_Processor_UserFeedbackMessageInners::class, PoP_Module_Processor_UserFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_MYPREFERENCES],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



