<?php

class PoP_Core_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_INVITENEWUSERS = 'feedbackmessage-inviteusers';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGE_INVITENEWUSERS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_INVITENEWUSERS => [PoP_Core_Module_Processor_FeedbackMessageInners::class, PoP_Core_Module_Processor_FeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_INVITENEWUSERS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



