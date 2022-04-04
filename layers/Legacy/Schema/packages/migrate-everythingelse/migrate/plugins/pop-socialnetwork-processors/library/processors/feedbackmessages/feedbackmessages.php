<?php

class PoP_SocialNetwork_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_CONTACTUSER = 'feedbackmessage-contactuser';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_CONTACTUSER],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_CONTACTUSER => [PoP_SocialNetwork_Module_Processor_FeedbackMessageInners::class, PoP_SocialNetwork_Module_Processor_FeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_CONTACTUSER],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



