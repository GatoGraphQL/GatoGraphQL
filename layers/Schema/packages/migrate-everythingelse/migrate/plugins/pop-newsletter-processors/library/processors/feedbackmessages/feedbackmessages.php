<?php

class PoP_Newsletter_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public const MODULE_FEEDBACKMESSAGE_NEWSLETTER = 'feedbackmessage-newsletter';
    public const MODULE_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION = 'feedbackmessage-newsletterunsubscription';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_NEWSLETTER],
            [self::class, self::MODULE_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_NEWSLETTER => [PoP_Newsletter_Module_Processor_FeedbackMessageInners::class, PoP_Newsletter_Module_Processor_FeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_NEWSLETTER],
            self::MODULE_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_FeedbackMessageInners::class, PoP_Newsletter_Module_Processor_FeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_NEWSLETTERUNSUBSCRIPTION],
        );

        if ($inner = $inners[$module[1]]) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



