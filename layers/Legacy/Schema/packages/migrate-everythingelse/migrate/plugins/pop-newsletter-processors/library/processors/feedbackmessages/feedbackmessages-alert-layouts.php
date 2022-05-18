<?php

class PoP_Newsletter_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTER = 'layout-feedbackmessagealert-newsletter';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTERUNSUBSCRIPTION = 'layout-feedbackmessagealert-newsletterunsubscription';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTER],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTER => [PoP_Newsletter_Module_Processor_FeedbackMessageLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_NEWSLETTER],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_FeedbackMessageLayouts::class, PoP_Newsletter_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



