<?php

class PoP_Share_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public const MODULE_FEEDBACKMESSAGE_SHAREBYEMAIL = 'feedbackmessage-sharebyemail';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_SHAREBYEMAIL],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_SHAREBYEMAIL => [PoP_Share_Module_Processor_FeedbackMessageInners::class, PoP_Share_Module_Processor_FeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_SHAREBYEMAIL],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



