<?php

class PoP_ContentCreation_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_FLAG = 'feedbackmessage-flag';
    public final const MODULE_FEEDBACKMESSAGE_CREATECONTENT = 'feedbackmessage-createcontent';
    public final const MODULE_FEEDBACKMESSAGE_UPDATECONTENT = 'feedbackmessage-updatecontent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_FLAG],
            [self::class, self::MODULE_FEEDBACKMESSAGE_CREATECONTENT],
            [self::class, self::MODULE_FEEDBACKMESSAGE_UPDATECONTENT],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_FLAG => [PoP_ContentCreation_Module_Processor_FeedbackMessageInners::class, PoP_ContentCreation_Module_Processor_FeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_FLAG],
            self::MODULE_FEEDBACKMESSAGE_CREATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageInners::class, PoP_ContentCreation_Module_Processor_FeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_CREATECONTENT],
            self::MODULE_FEEDBACKMESSAGE_UPDATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageInners::class, PoP_ContentCreation_Module_Processor_FeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_UPDATECONTENT],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



