<?php

class PoP_ContactUs_Module_Processor_FeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_CONTACTUS = 'feedbackmessage-contactus';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_CONTACTUS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_CONTACTUS => [PoP_ContactUs_Module_Processor_FeedbackMessageInners::class, PoP_ContactUs_Module_Processor_FeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_CONTACTUS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



