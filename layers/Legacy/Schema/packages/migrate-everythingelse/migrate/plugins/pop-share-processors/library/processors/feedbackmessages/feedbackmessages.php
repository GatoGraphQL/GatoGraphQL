<?php

class PoP_Share_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_SHAREBYEMAIL = 'feedbackmessage-sharebyemail';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGE_SHAREBYEMAIL,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_SHAREBYEMAIL => [PoP_Share_Module_Processor_FeedbackMessageInners::class, PoP_Share_Module_Processor_FeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_SHAREBYEMAIL],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



