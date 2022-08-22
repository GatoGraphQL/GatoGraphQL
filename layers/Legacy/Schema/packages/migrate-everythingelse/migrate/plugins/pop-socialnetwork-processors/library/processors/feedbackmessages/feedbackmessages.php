<?php

class PoP_SocialNetwork_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_CONTACTUSER = 'feedbackmessage-contactuser';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGE_CONTACTUSER,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_CONTACTUSER => [PoP_SocialNetwork_Module_Processor_FeedbackMessageInners::class, PoP_SocialNetwork_Module_Processor_FeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_CONTACTUSER],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



