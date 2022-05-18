<?php

class PoP_Module_Processor_ProfileFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_CREATEPROFILE = 'feedbackmessage-createprofile';
    public final const MODULE_FEEDBACKMESSAGE_UPDATEPROFILE = 'feedbackmessage-updateprofile';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_CREATEPROFILE],
            [self::class, self::MODULE_FEEDBACKMESSAGE_UPDATEPROFILE],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_CREATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageInners::class, PoP_Module_Processor_ProfileFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_CREATEPROFILE],
            self::MODULE_FEEDBACKMESSAGE_UPDATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageInners::class, PoP_Module_Processor_ProfileFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_UPDATEPROFILE],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



