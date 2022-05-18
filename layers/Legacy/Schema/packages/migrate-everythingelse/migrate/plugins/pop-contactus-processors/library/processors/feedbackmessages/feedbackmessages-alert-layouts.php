<?php

class PoP_ContactUs_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CONTACTUS = 'layout-feedbackmessagealert-contactus';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CONTACTUS],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CONTACTUS => [PoP_ContactUs_Module_Processor_FeedbackMessageLayouts::class, PoP_ContactUs_Module_Processor_FeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_CONTACTUS],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



