<?php

class PoP_Module_Processor_CreateLocationFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_CREATELOCATION = 'feedbackmessage-createlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_CREATELOCATION],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_CREATELOCATION => [PoP_Module_Processor_CreateLocationFeedbackMessageInners::class, PoP_Module_Processor_CreateLocationFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_CREATELOCATION],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



