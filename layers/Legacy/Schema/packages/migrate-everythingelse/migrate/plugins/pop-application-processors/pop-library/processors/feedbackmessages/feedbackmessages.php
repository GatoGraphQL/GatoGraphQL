<?php

class PoP_Module_Processor_DomainFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_ITEMLIST = 'feedbackmessage-itemlist';
    public final const MODULE_FEEDBACKMESSAGE_EMPTY = 'feedbackmessage-empty';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_ITEMLIST],
            [self::class, self::MODULE_FEEDBACKMESSAGE_EMPTY],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_ITEMLIST => [PoP_Module_Processor_ListFeedbackMessageInners::class, PoP_Module_Processor_ListFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_ITEMLIST],
            self::MODULE_FEEDBACKMESSAGE_EMPTY => [PoP_Module_Processor_DomainFeedbackMessageInners::class, PoP_Module_Processor_DomainFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_EMPTY],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



