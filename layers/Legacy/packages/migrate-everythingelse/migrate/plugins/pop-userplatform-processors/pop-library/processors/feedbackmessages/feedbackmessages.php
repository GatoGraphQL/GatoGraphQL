<?php

class PoP_Core_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public const MODULE_FEEDBACKMESSAGE_INVITENEWUSERS = 'feedbackmessage-inviteusers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_INVITENEWUSERS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_INVITENEWUSERS => [PoP_Core_Module_Processor_FeedbackMessageInners::class, PoP_Core_Module_Processor_FeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_INVITENEWUSERS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



