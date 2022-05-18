<?php

class PoP_Module_Processor_DomainFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_ITEMLIST = 'feedbackmessage-itemlist';
    public final const COMPONENT_FEEDBACKMESSAGE_EMPTY = 'feedbackmessage-empty';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_ITEMLIST],
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_EMPTY],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_ITEMLIST => [PoP_Module_Processor_ListFeedbackMessageInners::class, PoP_Module_Processor_ListFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_ITEMLIST],
            self::COMPONENT_FEEDBACKMESSAGE_EMPTY => [PoP_Module_Processor_DomainFeedbackMessageInners::class, PoP_Module_Processor_DomainFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_EMPTY],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



