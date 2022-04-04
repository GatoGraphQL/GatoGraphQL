<?php

class PoP_Module_Processor_DomainFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_ITEMLIST = 'layout-feedbackmessagealert-itemlist';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY = 'layout-feedbackmessagealert-empty';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_ITEMLIST],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_ITEMLIST => [PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY => [PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_EMPTY],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



