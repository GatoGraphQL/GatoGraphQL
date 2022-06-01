<?php

class PoP_Newsletter_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_NEWSLETTER = 'feedbackmessage-newsletter';
    public final const COMPONENT_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION = 'feedbackmessage-newsletterunsubscription';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGE_NEWSLETTER,
            self::COMPONENT_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_NEWSLETTER => [PoP_Newsletter_Module_Processor_FeedbackMessageInners::class, PoP_Newsletter_Module_Processor_FeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTER],
            self::COMPONENT_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_FeedbackMessageInners::class, PoP_Newsletter_Module_Processor_FeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



