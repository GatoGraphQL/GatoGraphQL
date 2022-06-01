<?php

class PoP_ContactUs_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_CONTACTUS = 'feedbackmessage-contactus';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_CONTACTUS],
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_CONTACTUS => [PoP_ContactUs_Module_Processor_FeedbackMessageInners::class, PoP_ContactUs_Module_Processor_FeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_CONTACTUS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



